@extends('layouts.kasir')

@section('title', 'Terminal Kasir')
@section('page_title', 'Terminal Kasir')
@section('page_description', 'Proses transaksi penjualan buah')

@section('content')
<div class="flex h-full w-full items-start overflow-hidden font-sans bg-slate-50/50">
    {{-- MAIN: DAFTAR PRODUK --}}
    <div class="flex flex-col flex-1 h-full overflow-hidden">

        {{-- HEADER KASIR --}}
        <div class="flex h-20 items-center justify-between px-8 bg-white border-b border-slate-200/60 shrink-0 box-border z-10 shadow-sm gap-6">
            {{-- SEARCH BAR --}}
            <div class="relative flex-1 max-w-md flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 text-slate-400 absolute left-4 pointer-events-none">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" />
                </svg>
                <input id="search-input" class="w-full bg-slate-50 rounded-xl border border-slate-200/80 py-2.5 pl-11 pr-5 text-slate-700 text-sm focus:outline-none focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500 focus:bg-white transition-all box-border" placeholder="Cari..." type="text" />
            </div>

            {{-- LIVE CLOCK --}}
            <div class="hidden lg:flex items-center gap-3 bg-slate-50 border border-slate-200/60 rounded-full px-4 py-2 shadow-inner select-none">
                <span class="flex h-2 w-2 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span id="session-clock" class="text-xs font-semibold text-slate-600 tracking-wide">
                    @php
                        \Carbon\Carbon::setLocale('id');
                        $now = now()->timezone('Asia/Jakarta');
                    @endphp
                    {{ $now->translatedFormat('l, d F Y') }} • <span class="text-brand-600 font-bold">{{ $now->format('H:i:s') }}</span> WIB
                </span>
            </div>

            {{-- USER PROFILE --}}
            <div class="flex items-center gap-3.5 select-none">
                @php
                    $hour = $now->hour;
                    if ($hour < 11) { $greeting = 'Pagi'; }
                    elseif ($hour < 15) { $greeting = 'Siang'; }
                    elseif ($hour < 18) { $greeting = 'Sore'; }
                    else { $greeting = 'Malam'; }
                    
                    $username = auth()->user()->name ?? 'Kasir';
                @endphp

                <div class="flex flex-col items-end text-right">
                    <span class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">
                        Selamat {{ $greeting }} 👋
                    </span>
                    <span class="text-sm font-extrabold text-slate-800 leading-tight">
                        {{ strtoupper($username) }}
                    </span>
                </div>

                {{-- Avatar Diganti Menjadi Ikon 👤 Minimalis & Modern --}}
                <div class="h-10 w-10 rounded-xl bg-slate-100 border border-slate-200/70 flex items-center justify-center text-xl shadow-inner overflow-hidden">
                    <span>👤</span>
                </div>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-8 flex flex-col gap-6 box-border custom-scrollbar">
            {{-- FILTER KATEGORI --}}
            <div id="kategori-filter" class="w-full flex items-center gap-2.5 overflow-x-auto pb-2 scrollbar-hide">
                <button type="button" data-kategori="all" class="kategori-btn px-5 py-2 bg-brand-600 text-white font-bold text-xs rounded-xl shadow-md shadow-brand-600/10 hover:shadow-lg transition-all active:scale-95 cursor-pointer whitespace-nowrap">Semua</button>
                @foreach($kategori as $k)
                    <button type="button" data-kategori="{{ $k }}" class="kategori-btn px-5 py-2 bg-white text-slate-600 font-semibold text-xs rounded-xl border border-slate-200/60 hover:border-slate-300 hover:bg-slate-50 transition-all active:scale-95 cursor-pointer whitespace-nowrap">{{ $k }}</button>
                @endforeach
            </div>

            {{-- GRID PRODUK --}}
            <div id="produk-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
                @forelse($buah as $item)
                    <div class="produk-card group flex flex-col bg-white rounded-2xl overflow-hidden border border-slate-200/60 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 {{ $item->stokHabis() ? 'opacity-60 grayscale-[40%]' : '' }}"
                         data-nama="{{ strtolower($item->nama_buah) }}"
                         data-kategori="{{ $item->kategori }}">
                        <div class="w-full h-36 bg-slate-50 overflow-hidden relative flex items-center justify-center p-4">
                            @if($item->gambar)
                                <img class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500" src="{{ asset('storage/'.$item->gambar) }}" alt="{{ $item->nama_buah }}" />
                            @else
                                <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center text-4xl shadow-inner group-hover:scale-105 transition-transform duration-300">🍎</div>
                            @endif
                            <div class="absolute top-3 left-3">
                                <span class="{{ $item->stokMenipis() ? 'bg-amber-50 text-amber-700 border border-amber-200' : ($item->stokHabis() ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-white text-slate-700 border border-slate-100') }} backdrop-blur-sm text-[10px] font-bold px-2.5 py-0.5 rounded-lg shadow-sm">
                                    Stok: {{ $item->stok }}
                                </span>
                            </div>
                        </div>
                        <div class="p-4 flex flex-col justify-between flex-1 gap-3">
                            <div>
                                <div class="font-bold text-slate-800 text-sm leading-snug tracking-tight group-hover:text-brand-600 transition-colors">{{ $item->nama_buah }}</div>
                                <div class="text-brand-600 text-base font-extrabold mt-1">Rp {{ number_format($item->harga, 0, ',', '.') }} <span class="text-slate-400 text-xs font-medium">/ {{ $item->satuan }}</span></div>
                            </div>
                            <button type="button"
                                    class="btn-tambah w-full bg-slate-50 hover:bg-brand-600 border border-slate-200/60 p-2.5 rounded-xl text-slate-700 hover:text-white cursor-pointer flex items-center justify-center gap-2 transition-all duration-200 active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed group/btn shadow-sm"
                                    data-id="{{ $item->id }}"
                                    data-nama="{{ $item->nama_buah }}"
                                    data-harga="{{ $item->harga }}"
                                    data-stok="{{ $item->stok }}"
                                    data-satuan="{{ $item->satuan }}"
                                    {{ $item->stokHabis() ? 'disabled' : '' }}>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                <span class="text-xs font-bold">Tambah</span>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-20 bg-white rounded-2xl border-2 border-dashed border-slate-200">
                        <span class="text-5xl mb-4">🛒</span>
                        <p class="text-slate-500 font-semibold text-sm">Belum ada produk terdaftar</p>
                        <p class="text-slate-400 text-xs mt-1">Silakan tambahkan data buah terlebih dahulu.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- KERANJANG --}}
    <div class="flex flex-col w-[400px] h-full bg-white border-l border-slate-200 shrink-0 box-border justify-between shadow-[-8px_0_24px_rgba(0,0,0,0.02)] z-20">
        <div class="flex items-center justify-between p-6 bg-white border-b border-slate-100 box-border">
            <div>
                <h2 class="font-bold text-slate-800 text-lg tracking-tight">Pesanan Baru</h2>
                <p class="text-slate-400 text-xs font-medium mt-0.5">Kelola item transaksi di sini</p>
            </div>
            <button id="btn-clear" type="button" class="bg-red-50 p-2.5 rounded-xl border-0 flex items-center justify-center cursor-pointer text-red-500 hover:bg-red-100 hover:text-red-600 transition-colors active:scale-95 shadow-sm" title="Kosongkan Keranjang">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
            </button>
        </div>

        <div id="cart-list" class="flex-1 overflow-y-auto p-6 flex flex-col gap-3.5 box-border bg-slate-50/50">
            <div id="cart-empty" class="h-full flex flex-col items-center justify-center text-center opacity-70 py-12">
                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center text-slate-400 mb-4 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" /></svg>
                </div>
                <p class="text-xs text-slate-500 font-semibold leading-relaxed">Keranjang Masih Kosong<br><span class="text-slate-400 font-medium">Klik tombol tambah pada produk</span></p>
            </div>
        </div>

        <div class="p-6 bg-white border-t border-slate-100 flex flex-col gap-4 shadow-[0px_-12px_30px_rgba(0,0,0,0.03)] box-border w-full z-10">
            <div class="flex items-center justify-between text-xs px-1">
                <span class="text-slate-500 font-semibold">Total Item</span>
                <span id="total-item" class="font-bold text-slate-700 bg-slate-100 px-2.5 py-0.5 rounded-lg">0</span>
            </div>
            <div class="flex items-center justify-between mb-1 px-1">
                <span class="text-slate-800 font-bold text-base">Total Bayar</span>
                <span id="total-harga" class="font-black text-brand-600 text-xl tracking-tight">Rp 0</span>
            </div>

            <button id="btn-bayar" type="button" disabled class="w-full bg-brand-600 disabled:bg-slate-200 disabled:text-slate-400 disabled:shadow-none border-0 text-white font-bold text-base py-4 rounded-xl flex items-center justify-center gap-2 shadow-lg shadow-brand-600/20 hover:bg-brand-700 hover:-translate-y-0.5 transition-all active:scale-95 cursor-pointer box-border group">
                <span>Proses Pembayaran</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 group-hover:translate-x-1 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
            </button>
        </div>
    </div>
</div>

{{-- MODAL PEMBAYARAN --}}
<div id="modal-bayar" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 items-center justify-center transition-opacity">
    <div class="bg-white rounded-2xl w-[460px] max-w-[90vw] p-8 shadow-2xl transform transition-transform scale-100 border border-slate-100">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-xl font-bold text-slate-800 tracking-tight">Pembayaran</h3>
                <p class="text-xs text-slate-400 mt-0.5">Selesaikan transaksi belanja pelanggan.</p>
            </div>
            <button class="btn-batal-bayar text-slate-400 hover:text-slate-600 bg-slate-100 hover:bg-slate-200 p-2 rounded-xl transition-colors active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <div class="bg-brand-50/60 border border-brand-100 rounded-xl p-4 flex items-center justify-between mb-5">
            <span class="text-brand-800 text-sm font-bold">Total Tagihan</span>
            <span id="modal-total" class="text-xl font-black text-brand-700 tracking-tight">Rp 0</span>
        </div>

        <div class="space-y-4 mb-6">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Metode Pembayaran</label>
                <div class="grid grid-cols-3 gap-2.5" id="payment-methods-grid">
                    <button type="button" data-value="tunai" class="metode-btn flex flex-col items-center gap-1.5 p-3 rounded-xl border-2 border-brand-600 bg-brand-50/50 text-brand-700 font-bold transition-all shadow-sm">
                        <span class="text-xs">Tunai</span>
                    </button>
                </div>
                <input type="hidden" id="metode-pembayaran" value="tunai">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jumlah Uang Diterima</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 font-extrabold text-slate-400 text-sm">Rp</span>
                    <input id="input-bayar" type="number" min="0" class="w-full border border-slate-200 bg-slate-50 rounded-xl pl-12 pr-4 py-3.5 text-lg font-black text-slate-800 focus:outline-none focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500 focus:bg-white transition-all shadow-inner" placeholder="0" />
                </div>
            </div>
            
            <div id="quick-cash" class="flex gap-2"></div>
        </div>

        <div class="flex items-center justify-between mb-6 pt-4 border-t border-slate-100">
            <span class="text-sm font-semibold text-slate-500">Kembalian</span>
            <span id="modal-kembalian" class="text-lg font-bold text-slate-400">Rp 0</span>
        </div>

        <p id="modal-error" class="hidden text-xs bg-red-50 text-red-600 border border-red-100 rounded-xl p-3 font-semibold mb-4 text-center shadow-sm"></p>

        <button id="btn-konfirmasi-bayar" type="button" class="w-full bg-brand-600 text-white rounded-xl py-4 font-bold text-base hover:bg-brand-700 shadow-md transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">Konfirmasi Transaksi</button>
    </div>
</div>

{{-- MODAL STRUK / RECEIPT --}}
<div id="modal-struk" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 items-center justify-center transition-opacity">
    <div class="bg-white rounded-2xl w-[380px] max-w-[90vw] p-8 shadow-2xl text-center relative overflow-hidden border border-slate-100">
        <div class="absolute top-0 left-0 w-full h-2 bg-emerald-500"></div>
        <div class="w-14 h-14 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3 mt-2 shadow-sm border border-emerald-100">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
        </div>
        <h3 class="text-xl font-bold text-slate-800 mb-1 tracking-tight">Transaksi Berhasil!</h3>
        <p id="struk-kode" class="text-[11px] font-bold text-slate-400 mb-5 bg-slate-50 inline-block px-3 py-1 rounded-md border border-slate-200/60">TRX-000</p>

        <div id="struk-detail" class="text-left bg-slate-50 border border-slate-200/60 rounded-xl p-4 mb-5 max-h-[200px] overflow-y-auto text-xs custom-scrollbar space-y-2 shadow-inner"></div>

        <div class="text-left text-xs mb-6 bg-slate-900 text-white rounded-xl p-4 shadow-lg space-y-2">
            <div class="flex justify-between items-center"><span class="text-slate-400 font-medium">Total</span><span id="struk-total" class="font-extrabold text-base text-white">Rp 0</span></div>
            <div class="w-full border-t border-slate-700 border-dashed my-1"></div>
            <div class="flex justify-between items-center"><span class="text-slate-400">Bayar</span><span id="struk-bayar" class="font-medium text-slate-200">Rp 0</span></div>
            <div class="flex justify-between items-center"><span class="text-slate-400 font-medium">Kembali</span><span id="struk-kembalian" class="font-bold text-emerald-400 text-sm">Rp 0</span></div>
        </div>

        <div class="flex gap-2.5">
            <a href="{{ route('kasir.riwayat') }}" class="flex-1 bg-white border border-slate-200 rounded-xl py-3 font-bold text-xs text-slate-600 hover:bg-slate-50 hover:text-slate-800 transition-colors text-decoration-none active:scale-95 flex items-center justify-center shadow-sm">Riwayat</a>
            <button id="btn-transaksi-baru" type="button" class="flex-[1.5] bg-brand-600 text-white rounded-xl py-3 font-bold text-xs hover:bg-brand-700 transition-all shadow-md active:scale-95">Selesai & Baru</button>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<script>
(function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const storeUrl = "{{ route('kasir.transaksi.store') }}";

    let cart = {}; 

    const cartListEl = document.getElementById('cart-list');
    const cartEmptyEl = document.getElementById('cart-empty');
    const totalItemEl = document.getElementById('total-item');
    const totalHargaEl = document.getElementById('total-harga');
    const btnBayar = document.getElementById('btn-bayar');

    function formatRupiah(angka) {
        return 'Rp ' + Number(angka || 0).toLocaleString('id-ID');
    }

    function hitungTotal() {
        return Object.values(cart).reduce((sum, i) => sum + (i.harga * i.qty), 0);
    }

    function hitungTotalItem() {
        return Object.values(cart).reduce((sum, i) => sum + i.qty, 0);
    }

    function renderCart() {
        const items = Object.values(cart);
        cartListEl.innerHTML = '';

        if (items.length === 0) {
            cartListEl.appendChild(cartEmptyEl);
            btnBayar.disabled = true;
        } else {
            btnBayar.disabled = false;
            items.forEach(item => {
                const row = document.createElement('div');
                row.className = 'flex items-center gap-3 bg-white rounded-xl border border-slate-200/80 p-3 shadow-sm group hover:border-brand-300 transition-all duration-200';
                row.innerHTML = `
                    <div class="flex-1 min-w-0 pl-0.5">
                        <div class="font-bold text-slate-800 text-xs truncate tracking-tight">${item.nama}</div>
                        <div class="text-slate-400 text-[10px] font-medium mt-0.5">${formatRupiah(item.harga)} <span class="text-slate-300">/${item.satuan}</span></div>
                    </div>
                    <div class="flex items-center bg-slate-50 rounded-lg p-0.5 border border-slate-200/60 shadow-inner">
                        <button type="button" class="btn-qty-min w-6 h-6 flex items-center justify-center rounded-md hover:bg-white hover:shadow-sm text-xs font-bold text-slate-600 transition-all active:scale-90" data-id="${item.id}">-</button>
                        <input type="number" step="any" min="0.01" max="${item.stok}" class="input-qty w-12 text-center font-bold text-xs text-slate-800 bg-transparent border-0 outline-none p-0 focus:ring-0" value="${item.qty}" data-id="${item.id}" />
                        <button type="button" class="btn-qty-plus w-6 h-6 flex items-center justify-center rounded-md hover:bg-white hover:shadow-sm text-xs font-bold text-brand-600 transition-all active:scale-90" data-id="${item.id}">+</button>
                    </div>
                    <div class="w-[72px] text-right font-extrabold text-xs text-slate-800 tracking-tight">${formatRupiah(item.harga * item.qty)}</div>
                    <button type="button" class="btn-hapus text-slate-300 hover:text-red-500 bg-transparent p-1.5 rounded-lg hover:bg-red-50 transition-all active:scale-90" data-id="${item.id}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                `;
                cartListEl.appendChild(row);
            });
        }

        totalItemEl.textContent = Number(hitungTotalItem()).toLocaleString('id-ID', { maximumFractionDigits: 2 });
        totalHargaEl.textContent = formatRupiah(hitungTotal());
    }

    document.querySelectorAll('.btn-tambah').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const stok = parseFloat(btn.dataset.stok);
            if (!cart[id]) {
                if (stok <= 0) return;
                cart[id] = {
                    id: id,
                    nama: btn.dataset.nama,
                    harga: parseInt(btn.dataset.harga, 10),
                    satuan: btn.dataset.satuan,
                    stok: stok,
                    qty: Math.min(stok, 1),
                };
            } else if (cart[id].qty < stok) {
                cart[id].qty = Math.min(stok, cart[id].qty + 1);
            }
            renderCart();
        });
    });

    cartListEl.addEventListener('change', (e) => {
        const input = e.target.closest('.input-qty');
        if (input) {
            const id = input.dataset.id;
            let val = parseFloat(input.value);
            if (isNaN(val) || val <= 0) {
                val = 0.01;
            }
            if (val > cart[id].stok) {
                val = cart[id].stok;
            }
            cart[id].qty = val;
            renderCart();
        }
    });

    cartListEl.addEventListener('input', (e) => {
        const input = e.target.closest('.input-qty');
        if (input) {
            const id = input.dataset.id;
            let val = parseFloat(input.value);
            if (!isNaN(val) && val > 0) {
                if (val > cart[id].stok) {
                    val = cart[id].stok;
                    input.value = val;
                }
                cart[id].qty = val;
                totalItemEl.textContent = Number(hitungTotalItem()).toLocaleString('id-ID', { maximumFractionDigits: 2 });
                totalHargaEl.textContent = formatRupiah(hitungTotal());
                const row = input.closest('.flex');
                if (row) {
                    const subtotalEl = row.querySelector('.w-\\[72px\\]');
                    if (subtotalEl) {
                        subtotalEl.textContent = formatRupiah(cart[id].harga * cart[id].qty);
                    }
                }
            }
        }
    });

    cartListEl.addEventListener('click', (e) => {
        const minus = e.target.closest('.btn-qty-min');
        const plus = e.target.closest('.btn-qty-plus');
        const hapus = e.target.closest('.btn-hapus');

        if (minus) {
            const id = minus.dataset.id;
            cart[id].qty = Math.max(0, cart[id].qty - 1);
            if (cart[id].qty <= 0) delete cart[id];
            renderCart();
        }
        if (plus) {
            const id = plus.dataset.id;
            if (cart[id].qty < cart[id].stok) {
                cart[id].qty = Math.min(cart[id].stok, cart[id].qty + 1);
            }
            renderCart();
        }
        if (hapus) {
            delete cart[hapus.dataset.id];
            renderCart();
        }
    });

    document.getElementById('btn-clear').addEventListener('click', () => {
        cart = {};
        renderCart();
    });

    document.querySelectorAll('.kategori-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.kategori-btn').forEach(b => {
                b.classList.remove('bg-brand-600', 'text-white', 'shadow-md', 'shadow-brand-600/10');
                b.classList.add('bg-white', 'text-slate-600', 'border', 'border-slate-200/60');
            });
            btn.classList.remove('bg-white', 'text-slate-600', 'border', 'border-slate-200/60');
            btn.classList.add('bg-brand-600', 'text-white', 'shadow-md', 'shadow-brand-600/10');

            const kategori = btn.dataset.kategori;
            document.querySelectorAll('.produk-card').forEach(card => {
                if (kategori === 'all' || card.dataset.kategori === kategori) {
                    card.style.display = '';
                    setTimeout(() => card.style.opacity = '1', 10);
                } else {
                    card.style.display = 'none';
                    card.style.opacity = '0';
                }
            });
        });
    });

    document.getElementById('search-input').addEventListener('input', (e) => {
        const q = e.target.value.trim().toLowerCase();
        document.querySelectorAll('.produk-card').forEach(card => {
            card.style.display = card.dataset.nama.includes(q) ? '' : 'none';
        });
    });

    setInterval(() => {
        const now = new Date();
        const tanggal = now.toLocaleDateString('id-ID', {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
        const jam = String(now.getHours()).padStart(2, '0') + ':' + 
                    String(now.getMinutes()).padStart(2, '0') + ':' + 
                    String(now.getSeconds()).padStart(2, '0');
        
        document.getElementById('session-clock').innerHTML = `${tanggal} • <span class="text-brand-600 font-bold">${jam}</span> WIB`;
    }, 1000);

    // ==== MODAL BAYAR ====
    const modalBayar = document.getElementById('modal-bayar');
    const inputBayar = document.getElementById('input-bayar');
    const modalTotal = document.getElementById('modal-total');
    const modalKembalian = document.getElementById('modal-kembalian');
    const modalError = document.getElementById('modal-error');
    const btnKonfirmasi = document.getElementById('btn-konfirmasi-bayar');
    const quickCashEl = document.getElementById('quick-cash');

    const metodeButtons = document.querySelectorAll('.metode-btn');
    const hiddenMetodeInput = document.getElementById('metode-pembayaran');
    
    metodeButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            metodeButtons.forEach(b => {
                b.classList.remove('border-brand-600', 'bg-brand-50/50', 'text-brand-700', 'border-2', 'shadow-sm');
                b.classList.add('border-slate-200', 'text-slate-600', 'font-semibold');
                b.classList.remove('font-bold');
            });
            btn.classList.remove('border-slate-200', 'text-slate-600', 'font-semibold');
            btn.classList.add('border-brand-600', 'bg-brand-50/50', 'text-brand-700', 'border-2', 'shadow-sm', 'font-bold');
            hiddenMetodeInput.value = btn.dataset.value;
        });
    });

    function bukaModalBayar() {
        const total = hitungTotal();
        modalTotal.textContent = formatRupiah(total);
        inputBayar.value = '';
        modalKembalian.textContent = formatRupiah(0);
        modalError.classList.add('hidden');

        const opsi = [total, Math.ceil(total / 5000) * 5000 + 5000, Math.ceil(total / 10000) * 10000 + 10000];
        quickCashEl.innerHTML = [...new Set(opsi)].map(v =>
            `<button type="button" class="btn-quick-cash flex-1 bg-white border border-slate-200 hover:border-brand-300 hover:bg-brand-50 rounded-xl py-2.5 text-xs font-bold text-slate-600 hover:text-brand-700 transition-all active:scale-95 shadow-sm" data-value="${v}">${formatRupiah(v)}</button>`
        ).join('');

        modalBayar.classList.remove('hidden');
        modalBayar.classList.add('flex');
    }

    function tutupModalBayar() {
        modalBayar.classList.add('hidden');
        modalBayar.classList.remove('flex');
    }

    quickCashEl.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn-quick-cash');
        if (btn) {
            inputBayar.value = btn.dataset.value;
            inputBayar.dispatchEvent(new Event('input'));
        }
    });

    inputBayar.addEventListener('input', () => {
        const total = hitungTotal();
        const bayar = parseInt(inputBayar.value || 0, 10);
        const kembalian = bayar - total;
        modalKembalian.textContent = formatRupiah(Math.max(kembalian, 0));
        modalKembalian.className = kembalian < 0 ? 'text-lg font-bold text-red-500' : 'text-lg font-black text-emerald-500';
    });

    document.getElementById('btn-bayar').addEventListener('click', bukaModalBayar);
    document.querySelectorAll('.btn-batal-bayar').forEach(b => b.addEventListener('click', tutupModalBayar));

    btnKonfirmasi.addEventListener('click', async () => {
        const total = hitungTotal();
        const bayar = parseInt(inputBayar.value || 0, 10);

        if (!bayar || bayar < total) {
            modalError.innerHTML = 'Jumlah uang bayar <b>kurang</b> dari total belanja.';
            modalError.classList.remove('hidden');
            return;
        }

        btnKonfirmasi.disabled = true;
        btnKonfirmasi.innerHTML = '<span class="flex items-center justify-center gap-2"><svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...</span>';

        try {
            const response = await fetch(storeUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    items: Object.values(cart).map(i => ({ buah_id: i.id, qty: i.qty })),
                    bayar: bayar,
                    metode_pembayaran: hiddenMetodeInput.value,
                }),
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                modalError.textContent = result.message || 'Terjadi kesalahan, silakan coba lagi.';
                modalError.classList.remove('hidden');
                btnKonfirmasi.disabled = false;
                btnKonfirmasi.textContent = 'Konfirmasi Transaksi';
                return;
            }

            tutupModalBayar();
            tampilkanStruk(result.transaksi);
            cart = {};
            renderCart();
        } catch (err) {
            modalError.textContent = 'Gagal menghubungi server. Periksa koneksi internet Anda.';
            modalError.classList.remove('hidden');
        } finally {
            btnKonfirmasi.disabled = false;
            btnKonfirmasi.textContent = 'Konfirmasi Transaksi';
        }
    });

    // ==== MODAL STRUK ====
    const modalStruk = document.getElementById('modal-struk');

    function tampilkanStruk(trx) {
        document.getElementById('struk-kode').textContent = trx.kode_transaksi + ' • ' + trx.created_at;
        document.getElementById('struk-total').textContent = formatRupiah(trx.total_harga);
        document.getElementById('struk-bayar').textContent = formatRupiah(trx.bayar);
        document.getElementById('struk-kembalian').textContent = formatRupiah(trx.kembalian);

        document.getElementById('struk-detail').innerHTML = trx.items.map(i => `
            <div class="flex justify-between items-center py-1.5 border-b border-slate-200/60 last:border-0">
                <div class="flex flex-col">
                    <span class="text-slate-800 font-bold">${i.nama_buah}</span>
                    <span class="text-slate-400 text-[10px] mt-0.5">${Number(i.qty).toLocaleString('id-ID', { maximumFractionDigits: 2 })} x ${formatRupiah(i.subtotal/i.qty)}</span>
                </div>
                <span class="font-bold text-slate-700">${formatRupiah(i.subtotal)}</span>
            </div>
        `).join('');

        modalStruk.classList.remove('hidden');
        modalStruk.classList.add('flex');
    }

    document.getElementById('btn-transaksi-baru').addEventListener('click', () => {
        modalStruk.classList.add('hidden');
        modalStruk.classList.remove('flex');
        window.location.reload();
    });

    renderCart();
})();
</script>
@endsection