@extends('layouts.admin')

@section('title', 'Laporan Transaksi')
@section('page_title', 'Laporan Transaksi')
@section('page_description', 'Rekap penjualan dan riwayat transaksi toko.')

@section('content')
<div class="flex flex-col gap-6">

    {{-- FILTER --}}
    <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block mb-1.5 text-xs font-semibold text-slate-700">Dari Tanggal</label>
                <input type="date" name="tanggal_awal" value="{{ $tanggalAwal }}" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm text-slate-800 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100 transition-all box-border">
            </div>
            <div>
                <label class="block mb-1.5 text-xs font-semibold text-slate-700">Sampai Tanggal</label>
                <input type="date" name="tanggal_akhir" value="{{ $tanggalAkhir }}" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm text-slate-800 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100 transition-all box-border">
            </div>
            <div>
                <label class="block mb-1.5 text-xs font-semibold text-slate-700">Metode Pembayaran</label>
                <select name="metode" class="rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100 transition-all box-border">
                    <option value="">Semua Metode</option>
                    <option value="tunai" {{ $metode === 'tunai' ? 'selected' : '' }}>Tunai</option>
                    <option value="qris" {{ $metode === 'qris' ? 'selected' : '' }}>QRIS</option>
                    <option value="debit" {{ $metode === 'debit' ? 'selected' : '' }}>Kartu Debit</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="rounded-xl bg-brand-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-brand-700 border-0 cursor-pointer transition-colors shadow-sm shadow-brand-600/10">Terapkan</button>
                <a href="{{ route('admin.laporan.index') }}" class="rounded-xl bg-slate-100 px-6 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-200 no-underline transition-colors flex items-center">Reset</a>
            </div>
        </form>
    </div>

    {{-- RINGKASAN --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Total Pendapatan</div>
            <div class="mt-2 text-2xl font-extrabold text-brand-600">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        </div>
        <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Jumlah Transaksi</div>
            <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $totalTransaksi }}</div>
        </div>
        <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Item Terjual</div>
            <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ (float) $totalItemTerjual }}</div>
        </div>
        <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Rata-rata / Transaksi</div>
            <div class="mt-2 text-2xl font-extrabold text-slate-900">Rp {{ number_format($rataRataTransaksi, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1.4fr_1fr] items-start">

        {{-- TABEL TRANSAKSI --}}
        <div class="rounded-2xl bg-white border border-slate-200 p-6 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 mb-4 pb-3 border-b border-slate-100">Daftar Transaksi</h2>

            @if($transaksi->isEmpty())
                <div class="text-center text-sm text-slate-400 py-10">Tidak ada transaksi pada rentang tanggal ini.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border-collapse">
                        <thead>
                            <tr class="text-left text-xs font-bold text-slate-500 uppercase tracking-wide border-b border-slate-200">
                                <th class="py-2 pr-4">Kode</th>
                                <th class="py-2 pr-4">Waktu</th>
                                <th class="py-2 pr-4">Kasir</th>
                                <th class="py-2 pr-4">Metode</th>
                                <th class="py-2 pr-2 text-right">Total</th>
                                <th class="py-2 pl-2 text-center">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksi as $trx)
                                <tr class="border-b border-slate-100 last:border-0 align-top">
                                    <td class="py-3 pr-4 font-semibold text-slate-900">{{ $trx->kode_transaksi }}</td>
                                    <td class="py-3 pr-4 text-slate-600 whitespace-nowrap">{{ $trx->created_at->format('d M Y H:i') }}</td>
                                    <td class="py-3 pr-4 text-slate-600">{{ $trx->user->name ?? '-' }}</td>
                                    <td class="py-3 pr-4 text-slate-600">{{ ucfirst($trx->metode_pembayaran) }}</td>
                                    <td class="py-3 pr-2 text-right font-bold text-brand-600 whitespace-nowrap">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                                    <td class="py-3 pl-2 text-center">
                                        <button type="button"
                                                class="btn-detail-laporan bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold px-3 py-1.5 rounded-lg border-0 cursor-pointer transition-colors"
                                                data-kode="{{ $trx->kode_transaksi }}"
                                                data-waktu="{{ $trx->created_at->format('d M Y H:i') }}"
                                                data-kasir="{{ $trx->user->name ?? '-' }}"
                                                data-metode="{{ ucfirst($trx->metode_pembayaran) }}"
                                                data-total="Rp {{ number_format($trx->total_harga, 0, ',', '.') }}"
                                                data-bayar="Rp {{ number_format($trx->bayar, 0, ',', '.') }}"
                                                data-kembalian="Rp {{ number_format($trx->kembalian, 0, ',', '.') }}"
                                                data-items='{{ $trx->items->map(fn($i) => ["nama" => $i->buah->nama_buah ?? "Produk dihapus", "qty" => (float)$i->qty, "subtotal" => "Rp " . number_format($i->subtotal, 0, ",", ".")])->toJson() }}'>
                                            Lihat
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100">
                    {{ $transaksi->links() }}
                </div>
            @endif
        </div>

        {{-- PRODUK TERLARIS --}}
        <div class="rounded-2xl bg-white border border-slate-200 p-6 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 mb-4 pb-3 border-b border-slate-100">Produk Terlaris</h2>

            @if($produkTerlaris->isEmpty())
                <div class="text-center text-sm text-slate-400 py-8">Belum ada data penjualan.</div>
            @else
                <div class="space-y-3">
                    @foreach($produkTerlaris as $i => $p)
                        <div class="flex items-center justify-between gap-3 rounded-xl border border-slate-100 bg-slate-50/50 p-3">
                            <div class="flex items-center gap-3">
                                <span class="flex w-7 h-7 shrink-0 items-center justify-center bg-brand-500 text-white text-xs font-bold rounded-lg">{{ $i + 1 }}</span>
                                <div>
                                    <div class="text-sm font-bold text-slate-900">{{ $p->buah->nama_buah ?? 'Produk dihapus' }}</div>
                                    <div class="text-xs text-slate-500">{{ (float)$p->total_qty }} terjual</div>
                                </div>
                            </div>
                            <div class="text-sm font-extrabold text-brand-600 whitespace-nowrap">Rp {{ number_format($p->total_omzet, 0, ',', '.') }}</div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>

{{-- MODAL DETAIL TRANSAKSI --}}
<div id="modal-detail-laporan" class="hidden fixed inset-0 bg-black/40 z-40 items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-[420px] max-w-full p-6 shadow-xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-1">
            <h3 class="text-xl font-bold text-slate-900 m-0">Detail Transaksi</h3>
            <button type="button" id="btn-tutup-detail-laporan" class="bg-transparent border-0 text-slate-400 hover:text-slate-700 cursor-pointer text-xl leading-none">&times;</button>
        </div>
        <p id="detail-kode" class="text-sm text-slate-500 mb-4"></p>

        <div class="bg-slate-50 rounded-xl p-4 space-y-1.5 mb-4 text-sm">
            <div class="flex justify-between"><span class="text-slate-600">Kasir</span><span id="detail-kasir" class="font-semibold text-slate-900"></span></div>
            <div class="flex justify-between"><span class="text-slate-600">Metode Pembayaran</span><span id="detail-metode" class="font-semibold text-slate-900"></span></div>
        </div>

        <div id="detail-items" class="border-t border-b border-dashed border-slate-200 py-3 mb-4 space-y-1.5 text-sm"></div>

        <div class="space-y-1.5 text-sm">
            <div class="flex justify-between"><span class="text-slate-600">Total Belanja</span><span id="detail-total" class="font-bold text-brand-600"></span></div>
            <div class="flex justify-between"><span class="text-slate-600">Jumlah Bayar</span><span id="detail-bayar" class="font-semibold text-slate-900"></span></div>
            <div class="flex justify-between"><span class="text-slate-600">Kembalian</span><span id="detail-kembalian" class="font-semibold text-emerald-700"></span></div>
        </div>

        <button type="button" id="btn-selesai-detail-laporan" class="w-full mt-5 bg-brand-600 hover:bg-brand-700 text-white font-semibold py-3 rounded-xl border-0 cursor-pointer transition-colors">Tutup</button>
    </div>
</div>

<script>
(function () {
    const modal = document.getElementById('modal-detail-laporan');

    function bukaModal(btn) {
        document.getElementById('detail-kode').textContent = btn.dataset.kode + ' • ' + btn.dataset.waktu;
        document.getElementById('detail-kasir').textContent = btn.dataset.kasir;
        document.getElementById('detail-metode').textContent = btn.dataset.metode;
        document.getElementById('detail-total').textContent = btn.dataset.total;
        document.getElementById('detail-bayar').textContent = btn.dataset.bayar;
        document.getElementById('detail-kembalian').textContent = btn.dataset.kembalian;

        const items = JSON.parse(btn.dataset.items || '[]');
        document.getElementById('detail-items').innerHTML = items.map(function (i) {
            const formattedQty = Number(i.qty).toLocaleString('id-ID', { maximumFractionDigits: 2 });
            return '<div class="flex justify-between"><span class="text-slate-700">' + i.nama + ' x' + formattedQty + '</span><span class="font-semibold text-slate-900">' + i.subtotal + '</span></div>';
        }).join('');

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function tutupModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.querySelectorAll('.btn-detail-laporan').forEach(function (btn) {
        btn.addEventListener('click', function () { bukaModal(btn); });
    });

    document.getElementById('btn-tutup-detail-laporan').addEventListener('click', tutupModal);
    document.getElementById('btn-selesai-detail-laporan').addEventListener('click', tutupModal);
    modal.addEventListener('click', function (e) { if (e.target === modal) tutupModal(); });
})();
</script>
@endsection
