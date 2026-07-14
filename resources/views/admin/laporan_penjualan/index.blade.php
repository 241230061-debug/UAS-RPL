@extends('layouts.admin')

@section('title', 'Laporan Penjualan')
@section('page_title', 'Laporan Penjualan')
@section('page_description', 'Pantau dan analisis data pendapatan serta transaksi penjualan buah.')

@section('content')
<div class="space-y-6">
    
    <!-- 1. RINGKASAN STATISTIK (CARDS) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Card Total Pendapatan -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-emerald-50 rounded-lg text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Pendapatan</p>
                <p class="text-xl font-bold text-slate-900 mt-1">Rp 15.250.000</p>
            </div>
        </div>

        <!-- Card Total Transaksi -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-brand-50 rounded-lg text-brand-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Transaksi</p>
                <p class="text-xl font-bold text-slate-900 mt-1">142 Transaksi</p>
            </div>
        </div>

        <!-- Card Buah Terlaris -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-orange-50 rounded-lg text-orange-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Produk Terlaris</p>
                <p class="text-xl font-bold text-slate-900 mt-1">Mangga Harum Manis</p>
            </div>
        </div>
    </div>

    <!-- 2. FILTER DATA -->
    <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
        <form action="" method="GET" class="flex flex-col sm:flex-row items-end gap-4">
            <div class="flex-1 w-full">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500">
            </div>
            <div class="flex-1 w-full">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Selesai</label>
                <input type="date" name="end_date" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500">
            </div>
            <div class="w-full sm:w-auto flex gap-2">
                <button type="submit" class="w-full sm:w-auto px-5 py-2 bg-slate-900 text-white text-sm font-semibold rounded-xl hover:bg-black transition cursor-pointer">
                    Filter
                </button>
                <button type="button" class="w-full sm:w-auto px-5 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 transition flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Excel
                </button>
            </div>
        </form>
    </div>

    <!-- 3. TABEL RIWAYAT TRANSAKSI -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
            <h3 class="text-base font-bold text-slate-900">Riwayat Transaksi</h3>
            <span class="text-xs text-slate-500 font-medium">Menampilkan data penjualan terakhir</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold">
                        <th class="px-6 py-3.5">No. Nota</th>
                        <th class="px-6 py-3.5">Tanggal</th>
                        <th class="px-6 py-3.5">Kasir</th>
                        <th class="px-6 py-3.5">Detail Item</th>
                        <th class="px-6 py-3.5 text-right">Total Transaksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <!-- Data dummy 1 -->
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 font-bold text-slate-900">#TRX-20260715-01</td>
                        <td class="px-6 py-4">15 Jul 2026 09:30</td>
                        <td class="px-6 py-4 font-medium">Siti Aminah</td>
                        <td class="px-6 py-4">
                            <span class="block text-xs font-medium text-slate-800">Apel Fuji (2 kg)</span>
                            <span class="block text-xs text-slate-400 mt-0.5">Melon Sunkist (1 pcs)</span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-brand-600">Rp 125.000</td>
                    </tr>
                    <!-- Data dummy 2 -->
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4 font-bold text-slate-900">#TRX-20260715-02</td>
                        <td class="px-6 py-4">15 Jul 2026 10:15</td>
                        <td class="px-6 py-4 font-medium">Budi Santoso</td>
                        <td class="px-6 py-4">
                            <span class="block text-xs font-medium text-slate-800">Mangga Harum Manis (5 kg)</span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-brand-600">Rp 175.000</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection