@extends('layouts.kasir')

@section('title', 'Riwayat Transaksi')
@section('page_title', 'Riwayat Transaksi')
@section('page_description', 'Daftar transaksi terakhir yang telah diproses oleh kasir.')

@section('content')
<div class="p-8 flex flex-col gap-6 box-border bg-slate-50/50 min-h-full font-sans">
    
    {{-- HEADER HALAMAN (Opsional jika tata letak bawaan belum menyediakannya, jika sudah ada bisa dihapus/disesuaikan) --}}
    <div class="flex items-center justify-between border-b border-slate-100 pb-4 select-none">
        <div>
            <h1 class="font-bold text-slate-800 text-2xl tracking-tight">Riwayat Transaksi</h1>
            <p class="text-slate-400 text-xs font-medium mt-0.5">Daftar transaksi terakhir yang berhasil diproses</p>
        </div>
        <a href="{{ route('kasir.dashboard') }}" class="inline-flex items-center gap-2 bg-white hover:bg-slate-50 border border-slate-200 px-4 py-2 rounded-xl text-sm font-bold text-slate-600 hover:text-slate-800 shadow-sm transition-all active:scale-95 text-decoration-none cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
            Kembali ke Kasir
        </a>
    </div>

    @if($transaksi->isEmpty())
        {{-- EMPTY STATE SENADA --}}
        <div class="flex flex-col items-center justify-center py-20 bg-white rounded-2xl border border-dashed border-slate-300 shadow-sm">
            <span class="text-5xl mb-4 animate-pulse">📜</span>
            <p class="text-slate-500 font-medium text-center">Belum ada riwayat transaksi yang tercatat hari ini.</p>
        </div>
    @else
        {{-- DAFTAR KARTU RIWAYAT --}}
        <div class="grid gap-5">
            @foreach($transaksi as $trx)
                <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 flex flex-col gap-4">
                    
                    {{-- HEADER KARTU --}}
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-slate-100/80 pb-4">
                        <div class="flex items-start gap-3.5">
                            <div class="p-3 bg-slate-50 rounded-xl text-slate-400 border border-slate-100 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2Z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-slate-400 tracking-wide">{{ $trx->created_at->format('d M Y, H:i') }} WIB</div>
                                <div class="text-lg font-black text-slate-800 tracking-tight mt-0.5">{{ $trx->kode_transaksi }}</div>
                            </div>
                        </div>
                        
                        <div class="flex flex-row sm:flex-col items-center sm:items-end justify-between sm:justify-center gap-2 select-none">
                            @php
                                $metode = strtolower($trx->metode_pembayaran);
                                $badgeColor = $metode === 'tunai' 
                                    ? 'bg-amber-50 text-amber-700 border-amber-100' 
                                    : ($metode === 'qris' ? 'bg-indigo-50 text-indigo-700 border-indigo-100' : 'bg-blue-50 text-blue-700 border-blue-100');
                                $icon = $metode === 'tunai' ? '💵' : ($metode === 'qris' ? '📱' : '💳');
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border {{ $badgeColor }}">
                                <span>{{ $icon }}</span>
                                <span>{{ $trx->metode_pembayaran }}</span>
                            </span>
                            <div class="text-xl font-black text-brand-600 tracking-tight">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    {{-- DETAIL ITEM DI DALAM KARTU --}}
                    <div class="rounded-2xl bg-slate-50/70 border border-slate-100/60 p-4 space-y-1">
                        @foreach($trx->items as $item)
                            <div class="flex justify-between items-center gap-4 text-sm text-slate-600 py-2 border-b border-slate-200/40 last:border-0">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-slate-800">{{ $item->buah->nama_buah }}</span>
                                    <span class="text-[11px] font-bold text-slate-400 bg-white px-2 py-0.5 rounded border border-slate-100 select-none">x{{ $item->qty }}</span>
                                </div>
                                <span class="font-bold text-slate-700">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                    
                </div>
            @endforeach
        </div>

        {{-- PAGINATION --}}
        <div class="mt-6">
            {{ $transaksi->links() }}
        </div>
    @endif
</div>
@endsection