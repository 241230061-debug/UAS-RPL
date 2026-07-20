<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
}
