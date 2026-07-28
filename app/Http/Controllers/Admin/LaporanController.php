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
        $totalItemTerjual = (float) TransaksiItem::whereIn('transaksi_id', (clone $query)->pluck('id'))->sum('qty');
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
     * Tampilkan laporan restok/pembelian buah dari supplier.
     */
    public function restokIndex(Request $request): View
    {
        $tanggalAwal = $request->query('tanggal_awal') ?: now()->startOfMonth()->format('Y-m-d');
        $tanggalAkhir = $request->query('tanggal_akhir') ?: now()->format('Y-m-d');
        $buahId = $request->query('buah_id');

        $mulai = Carbon::parse($tanggalAwal)->startOfDay();
        $selesai = Carbon::parse($tanggalAkhir)->endOfDay();

        $query = Restok::with(['buah', 'user'])
            ->whereBetween('created_at', [$mulai, $selesai])
            ->when($buahId, fn ($q) => $q->where('buah_id', $buahId));

        $totalTransaksi = (clone $query)->count();
        $totalJumlah = (float) (clone $query)->sum('jumlah');
        $totalBiaya = (int) (clone $query)->sum('total_biaya');

        $restok = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        $daftarBuah = Buah::orderBy('nama_buah')->get();

        return view('admin.laporan.restok', compact(
            'restok',
            'tanggalAwal',
            'tanggalAkhir',
            'buahId',
            'daftarBuah',
            'totalTransaksi',
            'totalJumlah',
            'totalBiaya',
        ));
    }

    /**
     * Tampilkan laporan buah rusak/busuk.
     *
     * Catatan perbaikan: sebelumnya method ini ikut menggabungkan data
     * Restok (buah masuk) ke dalam daftar yang ditampilkan, sehingga
     * data restok (mis. nama supplier) muncul seolah-olah sebagai baris
     * laporan buah rusak yang belum pernah diinput. Halaman ini khusus
     * untuk laporan buah rusak, jadi hanya data BuahRusak yang diambil.
     */
    public function buahIndex(Request $request): View
    {
        $tanggalAwal = $request->query('tanggal_awal') ?: now()->startOfMonth()->format('Y-m-d');
        $tanggalAkhir = $request->query('tanggal_akhir') ?: now()->format('Y-m-d');
        $buahId = $request->query('buah_id');

        $mulai = Carbon::parse($tanggalAwal)->startOfDay();
        $selesai = Carbon::parse($tanggalAkhir)->endOfDay();

        // Riwayat buah rusak/busuk saja
        $rusakQuery = BuahRusak::with(['buah', 'user'])
            ->whereBetween('created_at', [$mulai, $selesai])
            ->when($buahId, fn ($q) => $q->where('buah_id', $buahId));

        $totalRusak = (float) (clone $rusakQuery)->sum('jumlah');

        $riwayatPaginated = $rusakQuery
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString()
            ->through(fn ($item) => (object) [
                'jenis' => 'rusak',
                'buah' => $item->buah,
                'nama_buah' => $item->buah->nama_buah ?? null,
                'user' => $item->user,
                'jumlah' => $item->jumlah,
                'keterangan' => $item->catatan,
                'catatan' => $item->catatan,
                'nilai' => $item->buah ? $item->jumlah * $item->buah->harga : 0,
                'created_at' => $item->created_at,
            ]);

        $totalKerugianRusak = (int) (clone $rusakQuery)->get()
            ->sum(fn ($item) => $item->buah ? $item->jumlah * $item->buah->harga : 0);

        $daftarBuah = Buah::orderBy('nama_buah')->get();

        return view('admin.laporan.buah', compact(
            'riwayatPaginated',
            'tanggalAwal',
            'tanggalAkhir',
            'buahId',
            'daftarBuah',
            'totalRusak',
            'totalKerugianRusak',
        ));
    }
}