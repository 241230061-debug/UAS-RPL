@extends('layouts.admin')

@section('title', 'Laporan Restok')
@section('page_title', 'Laporan Restok')
@section('page_description', 'Rekap riwayat pembelian/restok buah dari supplier.')

@section('content')
<div class="flex flex-col gap-6">

    {{-- FILTER LAPORAN --}}
    <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
        <form method="GET" action="{{ route('admin.laporan.restok.index') }}" class="flex flex-wrap items-end gap-4">
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
                        <option value="{{ $b->id }}" {{ (string) $buahId === (string) $b->id ? 'selected' : '' }}>{{ $b->nama_buah }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="rounded-xl bg-brand-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-brand-700 border-0 cursor-pointer transition-colors shadow-sm shadow-brand-600/10">Terapkan</button>
                <a href="{{ route('admin.laporan.restok.index') }}" class="rounded-xl bg-slate-100 px-6 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-200 no-underline transition-colors flex items-center">Reset</a>
            </div>
        </form>
    </div>

    {{-- RINGKASAN --}}
    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Total Restok</div>
            <div class="text-xl font-extrabold text-slate-900 mt-1">{{ $totalTransaksi }} transaksi</div>
        </div>
        <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Total Jumlah Masuk</div>
            <div class="text-xl font-extrabold text-slate-900 mt-1">{{ number_format($totalJumlah, $totalJumlah == (int)$totalJumlah ? 0 : 2, ',', '.') }} Kg</div>
        </div>
        <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Total Biaya</div>
            <div class="text-xl font-extrabold text-brand-600 mt-1">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- TABEL RIWAYAT RESTOK --}}
    <div class="rounded-2xl bg-white border border-slate-200 p-6 shadow-sm">
        <h2 class="text-base font-bold text-slate-900 mb-4 pb-3 border-b border-slate-100">Daftar Riwayat Restok</h2>

        @if($restok->isEmpty())
            <div class="text-center text-sm text-slate-400 py-10">Tidak ada riwayat restok pada rentang tanggal atau filter ini.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="text-left text-xs font-bold text-slate-500 uppercase tracking-wide border-b border-slate-200">
                            <th class="py-2 pr-4">Tanggal</th>
                            <th class="py-2 pr-4">Buah</th>
                            <th class="py-2 pr-4">Supplier</th>
                            <th class="py-2 pr-4 text-center">Jumlah</th>
                            <th class="py-2 pr-4 text-right">Total Biaya</th>
                            <th class="py-2 pr-4">Petugas</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($restok as $item)
                        <tr class="border-b border-slate-100 last:border-0 align-top">
                            <td class="py-3 pr-4 text-slate-600 whitespace-nowrap">{{ $item->created_at->format('d M Y H:i') }}</td>
                            <td class="py-3 pr-4 font-semibold text-slate-900">{{ $item->buah->nama_buah ?? 'Produk dihapus' }}</td>
                            <td class="py-3 pr-4 text-slate-600">{{ $item->supplier }}</td>
                            <td class="py-3 pr-4 text-center font-bold text-emerald-600 whitespace-nowrap">{{ (float) $item->jumlah }} Kg</td>
                            <td class="py-3 pr-4 text-right font-semibold text-slate-800 whitespace-nowrap">Rp {{ number_format($item->total_biaya, 0, ',', '.') }}</td>
                            <td class="py-3 pr-4 text-slate-500 text-xs">{{ $item->user->name ?? 'Admin' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="mt-6 pt-4 border-t border-slate-100">
                {{ $restok->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
