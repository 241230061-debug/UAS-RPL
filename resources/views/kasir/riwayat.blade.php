@extends('layouts.kasir')

@section('title', 'Riwayat Transaksi')
@section('page_title', 'Riwayat Transaksi')
@section('page_description', 'Daftar transaksi terakhir yang telah diproses oleh kasir.')

@section('content')
<div class="p-6 box-border">
    @if($transaksi->isEmpty())
        <div class="rounded-2xl bg-white border border-slate-200 p-10 text-center text-slate-700 shadow-sm">
            Belum ada riwayat transaksi.
        </div>
    @else
        <div class="grid gap-4">
            @foreach($transaksi as $trx)
                <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <div class="text-sm text-slate-600">{{ $trx->created_at->format('d M Y H:i') }}</div>
                            <div class="text-lg font-bold text-slate-900">{{ $trx->kode_transaksi }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-slate-600">Metode: {{ ucfirst($trx->metode_pembayaran) }}</div>
                            <div class="text-lg font-bold text-brand-600">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <div class="mt-4 rounded-2xl bg-slate-50 p-4">
                        @foreach($trx->items as $item)
                            <div class="flex justify-between gap-3 text-sm text-slate-600 py-2 border-b border-slate-200 last:border-0">
                                <span>{{ $item->buah->nama_buah }} x{{ $item->qty }}</span>
                                <span class="font-semibold text-slate-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $transaksi->links() }}
        </div>
    @endif
</div>
@endsection
