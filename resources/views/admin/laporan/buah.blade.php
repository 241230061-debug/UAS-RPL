@extends('layouts.admin')

@section('title', 'Laporan Buah Rusak')
@section('page_title', 'Laporan Buah Rusak')
@section('page_description', 'Rekap data buah yang rusak, busuk, atau menyusut.')

@section('content')
<div class="flex flex-col gap-6">

    {{-- FILTER LAPORAN --}}
    <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
        <form method="GET" action="{{ route('admin.laporan.buah.index') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block mb-1.5 text-xs font-semibold text-slate-700">Dari Tanggal</label>
                <input type="date" name="tanggal_awal" value="{{ $tanggalAwal }}" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm text-slate-800 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100 transition-all box-border">
            </div>
            <div>
                <label class="block mb-1.5 text-xs font-semibold text-slate-700">Sampai Tanggal</label>
                <input type="date" name="tanggal_akhir" value="{{ $tanggalAkhir }}" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm text-slate-800 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100 transition-all box-border">
            </div>
            <div>
                <label class="block mb-1.5 text-xs font-semibold text-slate-700">Pilih Buah</label>
                <select name="buah_id" class="rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100 transition-all box-border">
                    <option value="">Semua Buah</option>
                    @foreach($daftarBuah as $b)
                        <option value="{{ $b->id }}" {{ request('buah_id') == $b->id ? 'selected' : '' }}>{{ $b->nama_buah }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="rounded-xl bg-brand-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-brand-700 border-0 cursor-pointer transition-colors shadow-sm shadow-brand-600/10">Terapkan</button>
                <a href="{{ route('admin.laporan.buah.index') }}" class="rounded-xl bg-slate-100 px-6 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-200 no-underline transition-colors flex items-center">Reset</a>
            </div>
        </form>
    </div>

    {{-- RINGKASAN --}}
    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Total Log Kerusakan</div>
            <div class="text-xl font-extrabold text-slate-900 mt-1">{{ number_format($riwayatPaginated->total(), 0, ',', '.') }} laporan</div>
        </div>
        <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Total Buah Rusak</div>
            <div class="text-xl font-extrabold text-red-600 mt-1">{{ number_format($totalRusak, $totalRusak == (int)$totalRusak ? 0 : 2, ',', '.') }} Kg</div>
        </div>
        <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Estimasi Kerugian</div>
            <div class="text-xl font-extrabold text-brand-600 mt-1">Rp {{ number_format($totalKerugianRusak, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- TABEL DATA BUAH RUSAK --}}
    <div class="rounded-2xl bg-white border border-slate-200 p-6 shadow-sm">
        <h2 class="text-base font-bold text-slate-900 mb-4 pb-3 border-b border-slate-100">Daftar Riwayat Buah Rusak</h2>

        @if($riwayatPaginated->isEmpty())
            <div class="text-center text-sm text-slate-400 py-10">Tidak ada riwayat buah rusak pada rentang tanggal atau filter ini.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="text-left text-xs font-bold text-slate-500 uppercase tracking-wide border-b border-slate-200">
                            <th class="py-2 pr-4">Tanggal Log</th>
                            <th class="py-2 pr-4">Nama Buah</th>
                            <th class="py-2 pr-4 text-center">Jumlah (Qty)</th>
                            <th class="py-2 pr-4">Keterangan / Alasan</th>
                            <th class="py-2 pr-4">Petugas</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($riwayatPaginated as $item)
                        <tr class="border-b border-slate-100 last:border-0 align-top">
                            {{-- Mengantisipasi format tanggal berupa String (Query Builder) atau Object (Eloquent) --}}
                            <td class="py-3 pr-4 text-slate-600 whitespace-nowrap">
                                {{ is_string($item->created_at) ? date('d M Y H:i', strtotime($item->created_at)) : ($item->created_at ? $item->created_at->format('d M Y H:i') : '-') }}
                            </td>
                            
                            {{-- Mengantisipasi nama_buah langsung dari Join Query ataupun via Relasi Model --}}
                            <td class="py-3 pr-4 font-semibold text-slate-900">
                                {{ $item->nama_buah ?? $item->buah->nama_buah ?? 'Produk dihapus' }}
                            </td>
                            
                            {{-- MEMPERBAIKI: Menggunakan properti 'jumlah' sesuai migration --}}
                            <td class="py-3 pr-4 text-center font-bold text-red-600">
                                {{ (float) ($item->jumlah ?? 0) }} Kg
                            </td>
                            
                            {{-- MEMPERBAIKI: Menggunakan properti 'catatan' sesuai migration --}}
                            <td class="py-3 pr-4 text-slate-600 max-w-xs break-words">
                                {{ $item->catatan ?? $item->keterangan ?? 'Tanpa keterangan' }}
                            </td>
                            
                            {{-- Mengantisipasi nama user/petugas dari select join ataupun via Relasi Model --}}
                            <td class="py-3 pr-4 text-slate-500 text-xs">
                                {{ $item->name ?? $item->nama_petugas ?? $item->user->name ?? 'Admin' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="mt-6 pt-4 border-t border-slate-100">
                {{ $riwayatPaginated->links() }}
            </div>
        @endif
    </div>
</div>
@endsection