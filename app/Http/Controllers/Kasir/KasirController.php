<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Buah;
use App\Models\Transaksi;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'bayar' => ['required', 'integer', 'min:0'],
            'metode_pembayaran' => ['required', 'string', 'in:tunai,qris,debit'],
        ]);

        $items = collect($data['items']);
        $buahIds = $items->pluck('buah_id')->unique();

        $buahList = Buah::whereIn('id', $buahIds)
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        $totalHarga = 0;
        $detailItems = [];

        foreach ($items as $item) {
            $buah = $buahList->get($item['buah_id']);
            if (! $buah) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan.',
                ], 404);
            }

            if ($item['qty'] > $buah->stok) {
                return response()->json([
                    'success' => false,
                    'message' => "Stok produk {$buah->nama_buah} tidak mencukupi.",
                ], 422);
            }

            $subtotal = $buah->harga * $item['qty'];
            $totalHarga += $subtotal;
            $detailItems[] = [
                'buah_id' => $buah->id,
                'qty' => $item['qty'],
                'harga' => $buah->harga,
                'subtotal' => $subtotal,
            ];
        }

        if ($data['bayar'] < $totalHarga) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah bayar kurang dari total belanja.',
            ], 422);
        }

        $transaksi = DB::transaction(function () use ($data, $detailItems, $totalHarga) {
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
                Buah::where('id', $item['buah_id'])->decrement('stok', $item['qty']);
            }

            return $transaksi;
        });

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
        $transaksi = Transaksi::with('items.buah')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('kasir.riwayat', compact('transaksi'));
    }
}
