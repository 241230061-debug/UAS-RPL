@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard')
@section('page_description', 'Kelola data dan pantau sistem Anda.')

@section('content')
<div class="flex flex-col gap-6">

    <div class="w-full bg-white rounded-xl border border-slate-200 p-6 box-border">
        <p class="m-0 text-slate-900 font-bold text-base">Selamat datang, {{ auth()->user()->name }} 👋</p>
        <p class="mt-2 text-slate-700 text-sm">
            Gunakan menu di kiri untuk mengelola Data Buah, Manajemen Pengguna (admin dan kasir), serta Pembelian & Restok.
        </p>
    </div>

    {{-- RINGKASAN LAPORAN HARI INI --}}
    <div>
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Laporan Hari Ini</h2>
            <a href="{{ route('admin.laporan.index') }}" class="text-xs font-bold text-brand-600 hover:text-brand-700 no-underline">Lihat Laporan Lengkap &rarr;</a>
        </div>
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Pendapatan Hari Ini</div>
                <div class="mt-2 text-2xl font-extrabold text-brand-600">Rp {{ number_format($totalPendapatanHariIni, 0, ',', '.') }}</div>
            </div>
            <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Jumlah Transaksi</div>
                <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $totalTransaksiHariIni }}</div>
            </div>
            <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Item Terjual</div>
                <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $totalItemTerjualHariIni }}</div>
            </div>
            <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Stok Menipis/Habis</div>
                <div class="mt-2 text-2xl font-extrabold {{ $totalStokMenipis > 0 ? 'text-amber-600' : 'text-slate-900' }}">{{ $totalStokMenipis }}</div>
            </div>
        </div>
    </div>

    {{-- TRANSAKSI TERBARU --}}
    <div class="rounded-2xl bg-white border border-slate-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <h2 class="text-base font-bold text-slate-900 m-0">Transaksi Terbaru</h2>
            <a href="{{ route('admin.laporan.index') }}" class="text-xs font-bold text-brand-600 hover:text-brand-700 no-underline">Lihat Semua &rarr;</a>
        </div>

        @if($transaksiTerbaru->isEmpty())
            <div class="text-center text-sm text-slate-400 py-8">Belum ada transaksi.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="text-left text-xs font-bold text-slate-500 uppercase tracking-wide border-b border-slate-200">
                            <th class="py-2 pr-4">Kode</th>
                            <th class="py-2 pr-4">Kasir</th>
                            <th class="py-2 pr-4">Waktu</th>
                            <th class="py-2 pr-4">Item</th>
                            <th class="py-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksiTerbaru as $trx)
                            <tr class="border-b border-slate-100 last:border-0">
                                <td class="py-3 pr-4 font-semibold text-slate-900">{{ $trx->kode_transaksi }}</td>
                                <td class="py-3 pr-4 text-slate-600">{{ $trx->user->name ?? '-' }}</td>
                                <td class="py-3 pr-4 text-slate-600">{{ $trx->created_at->format('d M Y H:i') }}</td>
                                <td class="py-3 pr-4 text-slate-600">{{ $trx->items->sum('qty') }} item</td>
                                <td class="py-3 text-right font-bold text-brand-600">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
