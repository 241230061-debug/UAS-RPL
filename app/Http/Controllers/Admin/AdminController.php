<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buah;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Tampilkan dashboard admin beserta ringkasan laporan singkat.
     */
    public function dashboard(): View
    {
        $hariIni = [now()->startOfDay(), now()->endOfDay()];

        $transaksiHariIni = Transaksi::whereBetween('created_at', $hariIni);
        $totalTransaksiHariIni = (clone $transaksiHariIni)->count();
        $totalPendapatanHariIni = (clone $transaksiHariIni)->sum('total_harga');
        $totalItemTerjualHariIni = (int) TransaksiItem::whereIn(
            'transaksi_id',
            (clone $transaksiHariIni)->pluck('id')
        )->sum('qty');

        $totalStokMenipis = Buah::where('aktif', true)
            ->get()
            ->filter(fn ($b) => $b->stokMenipis() || $b->stokHabis())
            ->count();

        $transaksiTerbaru = Transaksi::with(['items', 'user'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalTransaksiHariIni',
            'totalPendapatanHariIni',
            'totalItemTerjualHariIni',
            'totalStokMenipis',
            'transaksiTerbaru',
        ));
    }
}
