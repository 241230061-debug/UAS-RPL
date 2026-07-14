@extends('layouts.kasir')

@section('title', 'Terminal Kasir')
@section('page_title', 'Terminal Kasir')
@section('page_description', 'Proses transaksi penjualan buah')

@section('content')
<div class="flex h-full w-full items-start overflow-hidden">
    {{-- MAIN: DAFTAR PRODUK --}}
    <div class="flex flex-col flex-1 h-full bg-slate-50 overflow-hidden">

        <div class="flex h-16 items-center justify-between px-6 bg-white border-b border-slate-200 shrink-0 box-border">
            <div class="relative w-full max-w-md flex items-center">
                <input id="search-input" class="w-full bg-white rounded-lg border border-slate-300 py-2 pl-10 pr-4 text-slate-700 text-sm focus:outline-none focus:border-brand-500 shadow-sm box-border" placeholder="Cari produk..." type="text" />
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-[18px] h-[18px] text-slate-400 absolute left-3 pointer-events-none">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" />
                </svg>
            </div>

            <div class="flex items-center gap-6 select-none">
                <div class="flex flex-col items-end text-right">
                    <span class="text-brand-600 text-xs font-bold tracking-wider">KASIR: {{ strtoupper(auth()->user()->name ?? 'KASIR') }}</span>
                    <span id="session-clock" class="text-slate-600 text-[10px] font-medium opacity-80 mt-0.5">{{ now()->format('H:i:s') }}</span>
                </div>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-6 flex flex-col gap-6 box-border">

            <div id="kategori-filter" class="w-full flex items-center gap-3 overflow-x-auto pb-2 border-b border-transparent">
                <button type="button" data-kategori="all" class="kategori-btn px-5 py-2.5 bg-brand-600 text-white font-bold text-sm rounded-xl border-0 shadow-sm cursor-pointer whitespace-nowrap">Semua</button>
                @foreach($kategori as $k)
                    <button type="button" data-kategori="{{ $k }}" class="kategori-btn px-5 py-2.5 bg-slate-200 text-slate-700 font-bold text-sm rounded-xl border-0 hover:bg-slate-300 transition-colors cursor-pointer whitespace-nowrap">{{ $k }}</button>
                @endforeach
            </div>

            <div id="produk-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                @forelse($buah as $item)
                    <div class="produk-card flex flex-col bg-white rounded-xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-md transition-shadow duration-200 {{ $item->stokHabis() ? 'opacity-50' : '' }}"
                         data-nama="{{ strtolower($item->nama_buah) }}"
                         data-kategori="{{ $item->kategori }}">
                        <div class="w-full h-28 bg-slate-100 overflow-hidden relative flex items-center justify-center">
                            @if($item->gambar)
                                <img class="w-full h-full object-cover" src="{{ asset('storage/'.$item->gambar) }}" alt="{{ $item->nama_buah }}" />
                            @else
                                <span class="text-4xl">🍎</span>
                            @endif
                        </div>
                        <div class="p-3 flex flex-col justify-between flex-1 gap-2">
                            <div>
                                <div class="font-bold text-slate-900 text-base leading-snug">{{ $item->nama_buah }}</div>
                                <div class="text-slate-700 text-sm font-semibold mt-0.5">Rp {{ number_format($item->harga, 0, ',', '.') }} / {{ $item->satuan }}</div>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t border-slate-100">
                                <span class="{{ $item->stokMenipis() ? 'bg-amber-100 text-amber-800' : ($item->stokHabis() ? 'bg-red-100 text-red-800' : 'bg-emerald-100 text-emerald-800') }} text-[10px] font-bold px-1.5 py-0.5 rounded-sm">
                                    STOK: {{ $item->stok }}
                                </span>
                                <button type="button"
                                        class="btn-tambah bg-brand-600 border-0 p-1.5 rounded-lg text-white hover:bg-brand-700 cursor-pointer flex items-center justify-center transition-colors disabled:opacity-40 disabled:cursor-not-allowed"
                                        data-id="{{ $item->id }}"
                                        data-nama="{{ $item->nama_buah }}"
                                        data-harga="{{ $item->harga }}"
                                        data-stok="{{ $item->stok }}"
                                        data-satuan="{{ $item->satuan }}"
                                        {{ $item->stokHabis() ? 'disabled' : '' }}>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-700 col-span-full text-center py-10">Belum ada produk. Silakan tambahkan data buah terlebih dahulu.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- KERANJANG --}}
    <div class="flex flex-col w-[380px] h-full bg-slate-100 border-l border-slate-200 shrink-0 box-border justify-between">

        <div class="flex flex-col gap-3 p-4 bg-white border-b border-slate-200 box-border">
            <div class="flex items-center justify-between">
                <span class="font-semibold text-slate-900 text-lg">Transaksi Saat Ini</span>
                <button id="btn-clear" type="button" class="bg-transparent border-0 flex items-center gap-1 cursor-pointer text-red-600 hover:opacity-80 transition-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                    <span class="text-xs font-bold tracking-wider">KOSONGKAN</span>
                </button>
            </div>
        </div>

        <div id="cart-list" class="flex-1 overflow-y-auto p-4 flex flex-col gap-3 box-border">
            <p id="cart-empty" class="text-center text-sm text-slate-600 opacity-60 mt-8">Keranjang masih kosong.<br>Klik tombol (+) pada produk untuk mulai.</p>
        </div>

        <div class="p-4 bg-white border-t border-slate-200 flex flex-col gap-3 shadow-[0px_-4px_12px_rgba(0,0,0,0.04)] box-border w-full">
            <div class="flex items-center justify-between text-sm">
                <span class="text-slate-700 font-medium">Total Item</span>
                <span id="total-item" class="font-bold text-slate-900">0</span>
            </div>
            <div class="flex items-center justify-between text-lg">
                <span class="text-slate-700 font-semibold">Total Bayar</span>
                <span id="total-harga" class="font-bold text-brand-600">Rp 0</span>
            </div>

            <button id="btn-bayar" type="button" disabled class="w-full bg-brand-600 disabled:opacity-40 disabled:cursor-not-allowed border-0 text-white font-semibold text-lg py-4 rounded-xl flex items-center justify-center gap-3 shadow-md hover:bg-brand-700 transition-all cursor-pointer box-border">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75m0 .75v.75m0 .75v.75m0 .75V15h16.5V8.25m-16.5 0h16.5M3.75 8.25v7.5m16.5-7.5V5.25c0-.754-.726-1.294-1.453-1.096A60.065 60.065 0 0 0 3.75 4.5Z" />
                </svg>
                <span>Bayar</span>
            </button>
        </div>
    </div>
</div>

{{-- MODAL PEMBAYARAN --}}
<div id="modal-bayar" class="hidden fixed inset-0 bg-black/40 z-40 items-center justify-center">
    <div class="bg-white rounded-2xl w-[420px] max-w-[90vw] p-6 shadow-xl">
        <h3 class="text-xl font-bold text-slate-900 mb-1">Pembayaran</h3>
        <p class="text-sm text-slate-700 mb-4">Masukkan jumlah uang yang diterima dari pelanggan.</p>

        <div class="bg-slate-100 rounded-xl p-4 flex items-center justify-between mb-4">
            <span class="text-sm font-semibold text-slate-700">Total Belanja</span>
            <span id="modal-total" class="text-xl font-bold text-brand-600">Rp 0</span>
        </div>

        <label class="block text-sm font-semibold text-slate-700 mb-1">Metode Pembayaran</label>
        <select id="metode-pembayaran" class="w-full mb-4 border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-brand-600">
            <option value="tunai">Tunai</option>
            <option value="qris">QRIS</option>
            <option value="debit">Kartu Debit</option>
        </select>

        <label class="block text-sm font-semibold text-slate-700 mb-1">Jumlah Bayar</label>
        <input id="input-bayar" type="number" min="0" class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-lg font-semibold focus:outline-none focus:border-brand-600 mb-2" placeholder="0" />

        <div id="quick-cash" class="flex gap-2 mb-4"></div>

        <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-semibold text-slate-700">Kembalian</span>
            <span id="modal-kembalian" class="text-lg font-bold text-emerald-700">Rp 0</span>
        </div>

        <p id="modal-error" class="hidden text-sm text-red-600 font-medium mb-3"></p>

        <div class="flex gap-3">
            <button id="btn-batal-bayar" type="button" class="flex-1 border border-slate-300 rounded-lg py-3 font-bold text-slate-700 hover:bg-slate-50">Batal</button>
            <button id="btn-konfirmasi-bayar" type="button" class="flex-1 bg-brand-600 text-white rounded-lg py-3 font-bold hover:bg-brand-700 disabled:opacity-40 disabled:cursor-not-allowed">Konfirmasi</button>
        </div>
    </div>
</div>

{{-- MODAL STRUK / RECEIPT --}}
<div id="modal-struk" class="hidden fixed inset-0 bg-black/40 z-50 items-center justify-center">
    <div class="bg-white rounded-2xl w-[380px] max-w-[90vw] p-6 shadow-xl text-center">
        <div class="w-14 h-14 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center mx-auto mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
        </div>
        <h3 class="text-lg font-bold text-slate-900 mb-1">Transaksi Berhasil</h3>
        <p id="struk-kode" class="text-sm text-slate-600 mb-4">TRX-000</p>

        <div id="struk-detail" class="text-left bg-slate-50 rounded-xl p-4 mb-4 max-h-[220px] overflow-y-auto text-sm"></div>

        <div class="text-left text-sm mb-5 space-y-1">
            <div class="flex justify-between"><span class="text-slate-700">Total</span><span id="struk-total" class="font-bold">Rp 0</span></div>
            <div class="flex justify-between"><span class="text-slate-700">Bayar</span><span id="struk-bayar" class="font-semibold">Rp 0</span></div>
            <div class="flex justify-between"><span class="text-slate-700">Kembalian</span><span id="struk-kembalian" class="font-semibold text-emerald-700">Rp 0</span></div>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('kasir.riwayat') }}" class="flex-1 border border-slate-300 rounded-lg py-3 font-bold text-slate-700 hover:bg-slate-50 text-decoration-none">Lihat Riwayat</a>
            <button id="btn-transaksi-baru" type="button" class="flex-1 bg-brand-600 text-white rounded-lg py-3 font-bold hover:bg-brand-700">Transaksi Baru</button>
        </div>
    </div>
</div>

<script>
(function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const storeUrl = "{{ route('kasir.transaksi.store') }}";

    let cart = {}; // { buah_id: { id, nama, harga, qty, stok, satuan } }

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
                row.className = 'flex items-center gap-3 bg-white rounded-lg border border-slate-200 p-3';
                row.innerHTML = `
                    <div class="flex-1 min-w-0">
                        <div class="font-bold text-slate-900 text-sm truncate">${item.nama}</div>
                        <div class="text-slate-600 text-xs">${formatRupiah(item.harga)} / ${item.satuan}</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" class="btn-qty-min w-7 h-7 rounded-md bg-slate-200 font-bold text-slate-700 cursor-pointer" data-id="${item.id}">-</button>
                        <span class="w-6 text-center font-bold text-sm">${item.qty}</span>
                        <button type="button" class="btn-qty-plus w-7 h-7 rounded-md bg-slate-200 font-bold text-slate-700 cursor-pointer" data-id="${item.id}">+</button>
                    </div>
                    <div class="w-20 text-right font-bold text-sm text-brand-600">${formatRupiah(item.harga * item.qty)}</div>
                    <button type="button" class="btn-hapus text-red-600 cursor-pointer" data-id="${item.id}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                    </button>
                `;
                cartListEl.appendChild(row);
            });
        }

        totalItemEl.textContent = hitungTotalItem();
        totalHargaEl.textContent = formatRupiah(hitungTotal());
    }

    // Tambah ke keranjang
    document.querySelectorAll('.btn-tambah').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const stok = parseInt(btn.dataset.stok, 10);
            if (!cart[id]) {
                if (stok < 1) return;
                cart[id] = {
                    id: id,
                    nama: btn.dataset.nama,
                    harga: parseInt(btn.dataset.harga, 10),
                    satuan: btn.dataset.satuan,
                    stok: stok,
                    qty: 1,
                };
            } else if (cart[id].qty < stok) {
                cart[id].qty += 1;
            }
            renderCart();
        });
    });

    // +/- qty & hapus item (event delegation)
    cartListEl.addEventListener('click', (e) => {
        const minus = e.target.closest('.btn-qty-min');
        const plus = e.target.closest('.btn-qty-plus');
        const hapus = e.target.closest('.btn-hapus');

        if (minus) {
            const id = minus.dataset.id;
            cart[id].qty -= 1;
            if (cart[id].qty <= 0) delete cart[id];
            renderCart();
        }
        if (plus) {
            const id = plus.dataset.id;
            if (cart[id].qty < cart[id].stok) cart[id].qty += 1;
            renderCart();
        }
        if (hapus) {
            delete cart[hapus.dataset.id];
            renderCart();
        }
    });

    // Kosongkan keranjang
    document.getElementById('btn-clear').addEventListener('click', () => {
        cart = {};
        renderCart();
    });

    // Filter kategori
    document.querySelectorAll('.kategori-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.kategori-btn').forEach(b => {
                b.classList.remove('bg-brand-600', 'text-white');
                b.classList.add('bg-slate-200', 'text-slate-700');
            });
            btn.classList.remove('bg-slate-200', 'text-slate-700');
            btn.classList.add('bg-brand-600', 'text-white');

            const kategori = btn.dataset.kategori;
            document.querySelectorAll('.produk-card').forEach(card => {
                card.style.display = (kategori === 'all' || card.dataset.kategori === kategori) ? '' : 'none';
            });
        });
    });

    // Search produk
    document.getElementById('search-input').addEventListener('input', (e) => {
        const q = e.target.value.trim().toLowerCase();
        document.querySelectorAll('.produk-card').forEach(card => {
            card.style.display = card.dataset.nama.includes(q) ? '' : 'none';
        });
    });

    // Jam sesi berjalan
    setInterval(() => {
        document.getElementById('session-clock').textContent = new Date().toLocaleTimeString('id-ID');
    }, 1000);

    // ==== MODAL BAYAR ====
    const modalBayar = document.getElementById('modal-bayar');
    const inputBayar = document.getElementById('input-bayar');
    const modalTotal = document.getElementById('modal-total');
    const modalKembalian = document.getElementById('modal-kembalian');
    const modalError = document.getElementById('modal-error');
    const btnKonfirmasi = document.getElementById('btn-konfirmasi-bayar');
    const quickCashEl = document.getElementById('quick-cash');

    function bukaModalBayar() {
        const total = hitungTotal();
        modalTotal.textContent = formatRupiah(total);
        inputBayar.value = '';
        modalKembalian.textContent = formatRupiah(0);
        modalError.classList.add('hidden');

        const opsi = [total, Math.ceil(total / 5000) * 5000 + 5000, Math.ceil(total / 10000) * 10000 + 10000];
        quickCashEl.innerHTML = [...new Set(opsi)].map(v =>
            `<button type="button" class="btn-quick-cash flex-1 bg-slate-200 hover:bg-slate-300 rounded-lg py-2 text-xs font-bold text-slate-700 cursor-pointer" data-value="${v}">${formatRupiah(v)}</button>`
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
        modalKembalian.className = kembalian < 0 ? 'text-lg font-bold text-red-600' : 'text-lg font-bold text-emerald-700';
    });

    document.getElementById('btn-bayar').addEventListener('click', bukaModalBayar);
    document.getElementById('btn-batal-bayar').addEventListener('click', tutupModalBayar);

    btnKonfirmasi.addEventListener('click', async () => {
        const total = hitungTotal();
        const bayar = parseInt(inputBayar.value || 0, 10);

        if (!bayar || bayar < total) {
            modalError.textContent = 'Jumlah bayar kurang dari total belanja.';
            modalError.classList.remove('hidden');
            return;
        }

        btnKonfirmasi.disabled = true;
        btnKonfirmasi.textContent = 'Memproses...';

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
                    metode_pembayaran: document.getElementById('metode-pembayaran').value,
                }),
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                modalError.textContent = result.message || 'Terjadi kesalahan, coba lagi.';
                modalError.classList.remove('hidden');
                btnKonfirmasi.disabled = false;
                btnKonfirmasi.textContent = 'Konfirmasi';
                return;
            }

            tutupModalBayar();
            tampilkanStruk(result.transaksi);
            cart = {};
            renderCart();
        } catch (err) {
            modalError.textContent = 'Gagal menghubungi server. Periksa koneksi Anda.';
            modalError.classList.remove('hidden');
        } finally {
            btnKonfirmasi.disabled = false;
            btnKonfirmasi.textContent = 'Konfirmasi';
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
            <div class="flex justify-between py-1">
                <span class="text-slate-700">${i.nama_buah} x${i.qty}</span>
                <span class="font-semibold">${formatRupiah(i.subtotal)}</span>
            </div>
        `).join('');

        modalStruk.classList.remove('hidden');
        modalStruk.classList.add('flex');
    }

    document.getElementById('btn-transaksi-baru').addEventListener('click', () => {
        modalStruk.classList.add('hidden');
        modalStruk.classList.remove('flex');
        // Reload agar data stok produk yang tampil ter-update
        window.location.reload();
    });

    renderCart();
})();
</script>
@endsection