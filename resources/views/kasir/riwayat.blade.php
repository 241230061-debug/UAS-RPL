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
                        <div class="flex items-center gap-4">
                            <div class="text-right">
                                <div class="text-sm text-slate-600">Metode: {{ ucfirst($trx->metode_pembayaran) }}</div>
                                <div class="text-lg font-bold text-brand-600">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</div>
                            </div>
                            <button type="button"
                                    class="btn-detail-riwayat shrink-0 bg-brand-50 hover:bg-brand-100 text-brand-700 text-xs font-bold px-4 py-2.5 rounded-xl border-0 cursor-pointer transition-colors"
                                    data-kode="{{ $trx->kode_transaksi }}"
                                    data-waktu="{{ $trx->created_at->format('d M Y H:i') }}"
                                    data-metode="{{ ucfirst($trx->metode_pembayaran) }}"
                                    data-total="Rp {{ number_format($trx->total_harga, 0, ',', '.') }}"
                                    data-bayar="Rp {{ number_format($trx->bayar, 0, ',', '.') }}"
                                    data-kembalian="Rp {{ number_format($trx->kembalian, 0, ',', '.') }}"
                                    data-items='{{ $trx->items->map(fn($i) => ["nama" => $i->buah->nama_buah ?? "Produk dihapus", "qty" => $i->qty, "harga" => "Rp " . number_format($i->harga, 0, ",", "."), "subtotal" => "Rp " . number_format($i->subtotal, 0, ",", ".")])->toJson() }}'>
                                Detail Transaksi
                            </button>
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

{{-- MODAL DETAIL TRANSAKSI --}}
<div id="modal-detail-riwayat" class="hidden fixed inset-0 bg-black/40 z-40 items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-[420px] max-w-full p-6 shadow-xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-1">
            <h3 class="text-xl font-bold text-slate-900 m-0">Detail Transaksi</h3>
            <button type="button" id="btn-tutup-detail-riwayat" class="bg-transparent border-0 text-slate-400 hover:text-slate-700 cursor-pointer text-xl leading-none">&times;</button>
        </div>
        <p id="rw-kode" class="text-sm text-slate-500 mb-4"></p>

        <div class="bg-slate-50 rounded-xl p-4 mb-4 text-sm">
            <div class="flex justify-between"><span class="text-slate-600">Metode Pembayaran</span><span id="rw-metode" class="font-semibold text-slate-900"></span></div>
        </div>

        <div id="rw-items" class="border-t border-b border-dashed border-slate-200 py-3 mb-4 space-y-2 text-sm"></div>

        <div class="space-y-1.5 text-sm">
            <div class="flex justify-between"><span class="text-slate-600">Total Belanja</span><span id="rw-total" class="font-bold text-brand-600"></span></div>
            <div class="flex justify-between"><span class="text-slate-600">Jumlah Bayar</span><span id="rw-bayar" class="font-semibold text-slate-900"></span></div>
            <div class="flex justify-between"><span class="text-slate-600">Kembalian</span><span id="rw-kembalian" class="font-semibold text-emerald-700"></span></div>
        </div>

        <button type="button" id="btn-selesai-detail-riwayat" class="w-full mt-5 bg-brand-600 hover:bg-brand-700 text-white font-semibold py-3 rounded-xl border-0 cursor-pointer transition-colors">Tutup</button>
    </div>
</div>

<script>
(function () {
    const modal = document.getElementById('modal-detail-riwayat');

    function bukaModal(btn) {
        document.getElementById('rw-kode').textContent = btn.dataset.kode + ' • ' + btn.dataset.waktu;
        document.getElementById('rw-metode').textContent = btn.dataset.metode;
        document.getElementById('rw-total').textContent = btn.dataset.total;
        document.getElementById('rw-bayar').textContent = btn.dataset.bayar;
        document.getElementById('rw-kembalian').textContent = btn.dataset.kembalian;

        const items = JSON.parse(btn.dataset.items || '[]');
        document.getElementById('rw-items').innerHTML = items.map(function (i) {
            return '<div class="flex justify-between items-start"><span class="text-slate-700">' + i.nama + '<br><span class="text-xs text-slate-400">' + i.qty + ' x ' + i.harga + '</span></span><span class="font-semibold text-slate-900">' + i.subtotal + '</span></div>';
        }).join('');

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function tutupModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.querySelectorAll('.btn-detail-riwayat').forEach(function (btn) {
        btn.addEventListener('click', function () { bukaModal(btn); });
    });

    document.getElementById('btn-tutup-detail-riwayat').addEventListener('click', tutupModal);
    document.getElementById('btn-selesai-detail-riwayat').addEventListener('click', tutupModal);
    modal.addEventListener('click', function (e) { if (e.target === modal) tutupModal(); });
})();
</script>
@endsection
