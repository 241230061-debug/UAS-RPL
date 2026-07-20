<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buah;
use App\Models\BuahRusak;
use App\Models\Restok;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class LaporanController extends Controller
{
    /**
     * Tampilkan laporan transaksi (dengan filter rentang tanggal).
     */
    public function index(Request $request): View
    {
        $tanggalAwal = $request->query('tanggal_awal') ?: now()->startOfMonth()->format('Y-m-d');
        $tanggalAkhir = $request->query('tanggal_akhir') ?: now()->format('Y-m-d');
        $metode = $request->query('metode');

        $mulai = Carbon::parse($tanggalAwal)->startOfDay();
        $selesai = Carbon::parse($tanggalAkhir)->endOfDay();

        $query = Transaksi::with(['items.buah', 'user'])
            ->whereBetween('created_at', [$mulai, $selesai])
            ->when($metode, fn ($q) => $q->where('metode_pembayaran', $metode));

        // Ringkasan (dihitung sebelum pagination agar mencakup seluruh rentang tanggal)
        $ringkasanQuery = (clone $query);
        $totalTransaksi = $ringkasanQuery->count();
        $totalPendapatan = (clone $query)->sum('total_harga');
        $totalItemTerjual = (int) TransaksiItem::whereIn('transaksi_id', (clone $query)->pluck('id'))->sum('qty');
        $rataRataTransaksi = $totalTransaksi > 0 ? intdiv($totalPendapatan, $totalTransaksi) : 0;

        // Produk terlaris pada rentang tanggal terpilih
        $produkTerlaris = TransaksiItem::selectRaw('buah_id, SUM(qty) as total_qty, SUM(subtotal) as total_omzet')
            ->whereIn('transaksi_id', (clone $query)->pluck('id'))
            ->with('buah')
            ->groupBy('buah_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        $transaksi = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('admin.laporan.index', compact(
            'transaksi',
            'tanggalAwal',
            'tanggalAkhir',
            'metode',
            'totalTransaksi',
            'totalPendapatan',
            'totalItemTerjual',
            'rataRataTransaksi',
            'produkTerlaris',
        ));
    }

    /**
     * Tampilkan laporan pergerakan stok buah: masuk (restok/pembelian) dan rusak/busuk.
     */
    public function buahIndex(Request $request): View
    {
        $tanggalAwal = $request->query('tanggal_awal') ?: now()->startOfMonth()->format('Y-m-d');
        $tanggalAkhir = $request->query('tanggal_akhir') ?: now()->format('Y-m-d');
        $jenis = $request->query('jenis'); // 'masuk', 'rusak', atau kosong (semua)
        $buahId = $request->query('buah_id');

        $mulai = Carbon::parse($tanggalAwal)->startOfDay();
        $selesai = Carbon::parse($tanggalAkhir)->endOfDay();

        // Riwayat buah masuk (dari restok/pembelian)
        $restok = Restok::with(['buah', 'user'])
            ->whereBetween('created_at', [$mulai, $selesai])
            ->when($buahId, fn ($q) => $q->where('buah_id', $buahId))
            ->get()
            ->map(fn ($item) => (object) [
                'jenis' => 'masuk',
                'buah' => $item->buah,
                'user' => $item->user,
                'jumlah' => $item->jumlah,
                'keterangan' => $item->supplier,
                'catatan' => $item->catatan,
                'nilai' => $item->total_biaya,
                'created_at' => $item->created_at,
            ]);

        // Riwayat buah rusak/busuk
        $rusak = BuahRusak::with(['buah', 'user'])
            ->whereBetween('created_at', [$mulai, $selesai])
            ->when($buahId, fn ($q) => $q->where('buah_id', $buahId))
            ->get()
            ->map(fn ($item) => (object) [
                'jenis' => 'rusak',
                'buah' => $item->buah,
                'user' => $item->user,
                'jumlah' => $item->jumlah,
                'keterangan' => $item->catatan,
                'catatan' => $item->catatan,
                'nilai' => $item->buah ? $item->jumlah * $item->buah->harga : 0,
                'created_at' => $item->created_at,
            ]);

        // Gabungkan lalu filter jenis bila diminta
        $riwayat = new Collection([...$restok, ...$rusak]);

        if ($jenis === 'masuk' || $jenis === 'rusak') {
            $riwayat = $riwayat->where('jenis', $jenis);
        }

        $riwayat = $riwayat->sortByDesc('created_at')->values();

        // Ringkasan
        $totalMasuk = (int) $restok->sum('jumlah');
        $totalRusak = (int) $rusak->sum('jumlah');
        $totalBiayaRestok = (int) $restok->sum('nilai');
        $totalKerugianRusak = (int) $rusak->sum('nilai');

        // Pagination manual (data sudah digabung dari dua sumber)
        $page = (int) $request->query('page', 1);
        $perPage = 15;
        $riwayatPaginated = new LengthAwarePaginator(
            $riwayat->forPage($page, $perPage)->values(),
            $riwayat->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $daftarBuah = Buah::orderBy('nama_buah')->get();

        return view('admin.laporan.buah', compact(
            'riwayatPaginated',
            'tanggalAwal',
            'tanggalAkhir',
            'jenis',
            'buahId',
            'daftarBuah',
            'totalMasuk',
            'totalRusak',
            'totalBiayaRestok',
            'totalKerugianRusak',
        ));
    }
}