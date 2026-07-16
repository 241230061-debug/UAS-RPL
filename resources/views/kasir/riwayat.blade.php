@extends('layouts.kasir')

@section('title', 'Riwayat Transaksi')
@section('page_title', 'Riwayat Transaksi')
@section('page_description', 'Daftar transaksi terakhir yang telah diproses oleh kasir.')

@section('content')
<div class="p-8 flex flex-col gap-6 box-border bg-slate-50/50 min-h-full font-sans animate-fade-in">
    
    {{-- HEADER HALAMAN KASIR --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-slate-200/60 pb-5 select-none gap-4">
        <div>
            <h1 class="font-black text-slate-800 text-2xl tracking-tight">Riwayat Transaksi</h1>
            <p class="text-slate-400 text-xs font-medium mt-1">Daftar transaksi terakhir yang berhasil masuk ke sistem POS</p>
        </div>
        <a href="{{ route('kasir.dashboard') }}" class="inline-flex items-center justify-center gap-2 bg-white hover:bg-slate-50 border border-slate-200/80 px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:text-slate-800 shadow-sm transition-all active:scale-95 text-decoration-none cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 text-slate-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Terminal
        </a>
    </div>

    @if($transaksi->isEmpty())
        {{-- EMPTY STATE SENADA KASIR --}}
        <div class="flex flex-col items-center justify-center py-24 bg-white rounded-2xl border-2 border-dashed border-slate-200 shadow-sm">
            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 mb-4 shadow-inner border border-slate-100">
                <span class="text-3xl">📜</span>
            </div>
            <p class="text-slate-500 font-bold text-sm tracking-tight">Belum Ada Riwayat Transaksi</p>
            <p class="text-slate-400 text-xs mt-1 text-center max-w-xs px-4">Seluruh transaksi yang Anda selesaikan hari ini akan otomatis tercatat di halaman ini.</p>
        </div>
    @else
        {{-- DAFTAR KARTU RIWAYAT --}}
        <div class="grid gap-6">
            @foreach($transaksi as $trx)
                <div class="bg-white rounded-2xl border border-slate-200/60 p-6 shadow-sm hover:shadow-xl hover:border-slate-300/80 transition-all duration-300 flex flex-col gap-4 group relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-slate-200 group-hover:bg-brand-500 transition-colors"></div>
                    
                    {{-- HEADER KARTU --}}
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between border-b border-slate-100 pb-4 pl-2">
                        <div class="flex items-start gap-4">
                            <div class="p-3 bg-slate-50 rounded-xl text-slate-400 border border-slate-200/60 shrink-0 shadow-inner group-hover:bg-brand-50 group-hover:text-brand-500 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-[10px] font-bold text-slate-400 tracking-wider uppercase">
                                    {{ $trx->created_at->locale('id')->translatedFormat('l, d M Y • H:i') }} WIB
                                </div>
                                <div class="text-base font-black text-slate-800 tracking-tight mt-0.5 flex items-center gap-2">
                                    {{ $trx->kode_transaksi }}
                                </div>
                            </div>
                        </div>
                        
                        {{-- FINANSIAL METODE DAN NOMINAL --}}
                        <div class="flex flex-row md:flex-col items-center md:items-end justify-between md:justify-center gap-2 select-none border-t border-slate-100 pt-3 md:border-0 md:pt-0">
                            @php
                                $metode = strtolower($trx->metode_pembayaran);
                                $badgeColor = $metode === 'tunai' 
                                    ? 'bg-amber-50/80 text-amber-700 border-amber-200/60 shadow-amber-600/5' 
                                    : ($metode === 'qris' ? 'bg-indigo-50/80 text-indigo-700 border-indigo-200/60 shadow-indigo-600/5' : 'bg-blue-50/80 text-blue-700 border-blue-200/60 shadow-blue-600/5');
                                $icon = $metode === 'tunai' ? '💵' : ($metode === 'qris' ? '📱' : '💳');
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border shadow-sm {{ $badgeColor }}">
                                <span>{{ $icon }}</span>
                                <span>{{ $trx->metode_pembayaran }}</span>
                            </span>
                            <div class="text-xl font-black text-brand-600 tracking-tight group-hover:scale-105 transition-transform origin-right">
                                Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    {{-- DETAIL ITEM DI DALAM KARTU --}}
                    <div class="rounded-xl bg-slate-50/60 border border-slate-200/40 p-4 pl-6 space-y-0.5 shadow-inner">
                        @foreach($trx->items as $item)
                            <div class="flex justify-between items-center gap-4 text-xs text-slate-600 py-2.5 border-b border-dashed border-slate-200 last:border-0">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                    <span class="font-bold text-slate-800">{{ $item->buah->nama_buah ?? 'Produk Terhapus' }}</span>
                                    <span class="text-[10px] font-bold text-slate-400 bg-white px-2 py-0.5 rounded-md border border-slate-200/60 shadow-sm select-none">
                                        {{ $item->qty }} {{ $item->buah->satuan ?? 'Pcs' }}
                                    </span>
                                </div>
                                <span class="font-extrabold text-slate-700">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                    
                    {{-- DETAIL CASHFLOW KEMBALIAN (JIKA ADA DATA BAYAR) --}}
                    @if(isset($trx->bayar) && $trx->bayar > 0)
                        <div class="flex items-center justify-end gap-5 text-[11px] text-slate-400 font-medium px-2 select-none">
                            <div>Diterima: <span class="font-bold text-slate-600">Rp {{ number_format($trx->bayar, 0, ',', '.') }}</span></div>
                            <div class="w-1 h-1 bg-slate-300 rounded-full"></div>
                            <div>Kembalian: <span class="font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">Rp {{ number_format($trx->kembalian, 0, ',', '.') }}</span></div>
                        </div>
                    @endif
                    
                </div>
            @endforeach
        </div>

        {{-- PAGINATION CUSTOM MODERN --}}
        <div class="mt-8 flex justify-center custom-pagination">
            {{ $transaksi->links() }}
        </div>
    @endif
</div>

<style>
    /* Animasi fade in halus */
    .animate-fade-in {
        animation: fadeIn 0.4s ease-out forwards;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Normalisasi style link bawaan laravel pagination */
    .custom-pagination nav {
        @apply shadow-sm rounded-xl overflow-hidden border border-slate-200/80 bg-white p-1;
    }
</style>
@endsection