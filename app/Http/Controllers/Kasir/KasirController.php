<?php

namespace App\Http\Controllers\Kasir;

use App\Exceptions\TransaksiGagalException;
use App\Http\Controllers\Controller;
use App\Models\Buah;
use App\Models\Transaksi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class KasirController extends Controller
{
    /**
     * Tampilkan dashboard/terminal kasir.
     */
    public function dashboard(): View
    {
        $buah = Buah::where('aktif', true)
            ->orderBy('nama_buah')
            ->get();

        $kategori = Buah::where('aktif', true)
            ->whereNotNull('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori');

        return view('kasir.dashboard', compact('buah', 'kategori'));
    }

    /**
     * Simpan transaksi dari kasir.
     */
    public function storeTransaksi(Request $request): JsonResponse
    {
        $data = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.buah_id' => ['required', 'integer', 'exists:buah,id'],
            'items.*.qty' => ['required', 'numeric', 'gt:0'],
            'bayar' => ['required', 'integer', 'min:0'],
            'metode_pembayaran' => ['required', 'string', 'in:tunai,qris,debit'],
        ]);

        // Gabungkan qty untuk buah_id yang sama, supaya validasi stok tetap akurat
        // meskipun payload berisi baris duplikat untuk produk yang sama.
        $itemsGrouped = collect($data['items'])
            ->groupBy('buah_id')
            ->map(fn ($group, $buahId) => [
                'buah_id' => (int) $buahId,
                'qty' => (float) $group->sum('qty'),
            ])
            ->values();

        $buahIds = $itemsGrouped->pluck('buah_id');

        try {
            $transaksi = DB::transaction(function () use ($data, $itemsGrouped, $buahIds) {
                // lockForUpdate() HARUS di dalam transaction agar benar-benar mengunci
                // baris stok sampai transaksi ini commit — mencegah dua kasir checkout
                // produk yang sama secara bersamaan membuat stok jadi minus.
                $buahList = Buah::whereIn('id', $buahIds)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                $totalHarga = 0;
                $detailItems = [];

                foreach ($itemsGrouped as $item) {
                    $buah = $buahList->get($item['buah_id']);

                    if (! $buah) {
                        throw new TransaksiGagalException('Produk tidak ditemukan.', 404);
                    }

                    if ($item['qty'] > $buah->stok) {
                        throw new TransaksiGagalException(
                            "Stok produk {$buah->nama_buah} tidak mencukupi."
                        );
                    }

                    $subtotal = (int) round($buah->harga * $item['qty']);
                    $totalHarga += $subtotal;
                    $detailItems[] = [
                        'buah_id' => $buah->id,
                        'qty' => $item['qty'],
                        'harga' => $buah->harga,
                        'subtotal' => $subtotal,
                    ];
                }

                if ($data['bayar'] < $totalHarga) {
                    throw new TransaksiGagalException('Jumlah bayar kurang dari total belanja.');
                }

                $transaksi = Transaksi::create([
                    'kode_transaksi' => 'TRX-' . strtoupper(Str::random(8)),
                    'user_id' => auth()->id(),
                    'metode_pembayaran' => $data['metode_pembayaran'],
                    'total_harga' => $totalHarga,
                    'bayar' => $data['bayar'],
                    'kembalian' => $data['bayar'] - $totalHarga,
                ]);

                foreach ($detailItems as $item) {
                    $transaksi->items()->create($item);

                    // decrement di dalam transaction yang sama dengan lock di atas,
                    // jadi baris stok ini tetap terkunci sampai proses ini selesai.
                    Buah::where('id', $item['buah_id'])->decrement('stok', $item['qty']);
                }

                return $transaksi;
            });
        } catch (TransaksiGagalException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->status);
        }

        $transaksi->load(['items.buah']);

        return response()->json([
            'success' => true,
            'transaksi' => [
                'kode_transaksi' => $transaksi->kode_transaksi,
                'created_at' => $transaksi->created_at->format('Y-m-d H:i:s'),
                'total_harga' => $transaksi->total_harga,
                'bayar' => $transaksi->bayar,
                'kembalian' => $transaksi->kembalian,
                'items' => $transaksi->items->map(function ($item) {
                    return [
                        'nama_buah' => $item->buah->nama_buah,
                        'qty' => $item->qty,
                        'subtotal' => $item->subtotal,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Tampilkan riwayat transaksi kasir.
     */
    public function riwayat(): View
    {
        $transaksi = Transaksi::with(['items.buah', 'user'])
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('kasir.riwayat', compact('transaksi'));
    }
}
