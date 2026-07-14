@extends('layouts.admin')

@section('title', 'Data Master')
@section('page_title', 'Data Master Buah')
@section('page_description', 'Kelola data inventaris komoditas buah di sini.')

@section('content')
@if(!empty($lowStockCount) && $lowStockCount > 0)
    <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900 shadow-sm">
        <div class="flex items-center gap-2 font-semibold text-amber-800">
            <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            Peringatan Stok Menipis
        </div>
        <span class="block mt-1 text-amber-700">Ada <strong>{{ $lowStockCount }} produk</strong> dengan jumlah stok ≤ {{ $lowStockThreshold }}. Harap lakukan restok berkala.</span>
    </div>
@endif

<!-- GRID SYSTEM -->
<div class="grid gap-8 lg:grid-cols-[1.3fr_1fr] items-start">
                
                <!-- LEFT: FORM TAMBAH BUAH -->
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <div class="mb-6">
                        <h2 class="text-base font-bold text-slate-900 m-0">Tambah Produk Baru</h2>
                        <p class="text-xs text-slate-500 mt-1">Kelola data inventaris komoditas buah di sini.</p>
                    </div>

                    <form action="{{ route('admin.buah.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="block mb-1.5 text-xs font-semibold text-slate-700 uppercase tracking-wider">Kode Produk</label>
                                <input type="text" name="kode" value="{{ old('kode') }}" class="w-full rounded-lg border border-slate-300 bg-slate-50 focus:bg-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500 px-3 py-2 text-sm transition-all outline-none" placeholder="Contoh: AP-01" required>
                                @error('kode')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block mb-1.5 text-xs font-semibold text-slate-700 uppercase tracking-wider">Nama Buah</label>
                                <input type="text" name="nama_buah" value="{{ old('nama_buah') }}" class="w-full rounded-lg border border-slate-300 bg-slate-50 focus:bg-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500 px-3 py-2 text-sm transition-all outline-none" placeholder="Contoh: Apel Fuji" required>
                                @error('nama_buah')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-3">
                            <div>
                                <label class="block mb-1.5 text-xs font-semibold text-slate-700 uppercase tracking-wider">Kategori</label>
                                <input type="text" name="kategori" value="{{ old('kategori') }}" class="w-full rounded-lg border border-slate-300 bg-slate-50 focus:bg-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500 px-3 py-2 text-sm transition-all outline-none" placeholder="Lokal / Impor">
                                @error('kategori')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block mb-1.5 text-xs font-semibold text-slate-700 uppercase tracking-wider">Harga Jual (Rp)</label>
                                <input type="number" name="harga" value="{{ old('harga') }}" class="w-full rounded-lg border border-slate-300 bg-slate-50 focus:bg-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500 px-3 py-2 text-sm transition-all outline-none" min="0" placeholder="0" required>
                                @error('harga')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block mb-1.5 text-xs font-semibold text-slate-700 uppercase tracking-wider">Stok Awal</label>
                                <div class="flex rounded-lg border border-slate-300 bg-slate-50 focus-within:bg-white focus-within:border-brand-500 focus-within:ring-1 focus-within:ring-brand-500 overflow-hidden transition-all">
                                    <input type="number" name="stok" value="{{ old('stok', 0) }}" class="w-full bg-transparent border-0 px-3 py-2 text-sm outline-none" min="0" required>
                                    <input type="text" name="satuan" value="{{ old('satuan', 'kg') }}" class="w-16 text-center bg-slate-200 border-0 border-l border-slate-300 text-xs font-semibold text-slate-600 outline-none uppercase" required>
                                </div>
                                @error('stok')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                                @error('satuan')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div>
                            <label class="block mb-1.5 text-xs font-semibold text-slate-700 uppercase tracking-wider">Foto Produk</label>
                            <input type="file" name="gambar" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-300 rounded-lg p-1.5 bg-slate-50">
                            @error('gambar')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block mb-1.5 text-xs font-semibold text-slate-700 uppercase tracking-wider">Keterangan Tambahan</label>
                            <textarea name="keterangan" rows="3" class="w-full rounded-lg border border-slate-300 bg-slate-50 focus:bg-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500 px-3 py-2 text-sm transition-all outline-none resize-none" placeholder="Catatan spesifikasi kondisi buah..."></textarea>
                            @error('keterangan')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                            <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700 cursor-pointer select-none">
                                <input type="checkbox" name="aktif" value="1" {{ old('aktif', true) ? 'checked' : '' }} class="rounded border-slate-300 text-brand-600 focus:ring-brand-500 w-4 h-4">
                                Tampilkan di Toko
                            </label>
                            <button type="submit" class="rounded-lg bg-brand-600 px-5 py-2 text-sm font-semibold text-white hover:bg-brand-700 active:bg-brand-700 transition-colors shadow-sm">Simpan Produk</button>
                        </div>
                    </form>
                </div>

                <!-- RIGHT: DAFTAR BUAH -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-bold text-slate-900 m-0">Katalog Terdaftar</h2>
                    </div>
                    
                    <div class="space-y-4">
                        @forelse($buah as $item)
                            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm hover:border-slate-300 transition-all">
                                <div class="flex items-start gap-4">
                                    <!-- Container Gambar Terstandarisasi -->
                                    <div class="w-16 h-16 rounded-lg bg-slate-100 overflow-hidden flex items-center justify-center shrink-0 border border-slate-200">
                                        @if($item->gambar)
                                            <img src="{{ asset('storage/'.$item->gambar) }}" alt="{{ $item->nama_buah }}" class="h-full w-full object-cover">
                                        @else
                                            <!-- SVG Placeholder Modern & Bersih (Ganti Emoji) -->
                                            <svg class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4.5M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                            </svg>
                                        @endif
                                    </div>
                                    
                                    <!-- Info Produk -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-2">
                                            <div class="font-bold text-slate-900 text-sm truncate">{{ $item->nama_buah }}</div>
                                            <span class="text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 px-2 py-0.5 rounded">
                                                {{ $item->kategori ?: 'Umum' }}
                                            </span>
                                        </div>
                                        <div class="text-sm font-semibold text-brand-600 mt-1">
                                            Rp {{ number_format($item->harga, 0, ',', '.') }}<span class="text-xs text-slate-400 font-normal"> / {{ $item->satuan }}</span>
                                        </div>
                                        
                                        <div class="flex items-center gap-3 mt-2 text-xs text-slate-500 font-medium">
                                            <span>Stok saat ini: <strong class="text-slate-800">{{ $item->stok }} {{ $item->satuan }}</strong></span>
                                            @if($item->stok <= $lowStockThreshold)
                                                <span class="rounded-md bg-rose-50 px-2 py-0.5 text-[11px] font-semibold text-rose-700 border border-rose-200">Kritis</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <hr class="border-slate-100 my-4" />

                                <!-- Aksi & Pelaporan Kerusakan -->
                                <div class="space-y-3">
                                    <form action="{{ route('admin.buah.rusak', $item) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        <input type="number" name="jumlah_rusak" min="1" max="{{ $item->stok }}" value="{{ old('jumlah_rusak') }}" class="w-full rounded-md border border-slate-200 px-3 py-1.5 text-xs outline-none focus:border-rose-500 transition-colors" placeholder="Jumlah busuk/rusak" required>
                                        <button type="submit" class="shrink-0 rounded-md bg-rose-50 border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-100 transition-colors whitespace-nowrap">
                                            Laporkan Rusak
                                        </button>
                                    </form>
                                    
                                    @if($errors->has('jumlah_rusak'))
                                        <p class="text-[11px] text-rose-600 m-0">{{ $errors->first('jumlah_rusak') }}</p>
                                    @endif

                                    <div class="flex items-center justify-end gap-1.5 pt-1">
                                        <a href="{{ route('admin.restok.index', ['buah_id' => $item->id]) }}" class="inline-flex items-center justify-center rounded-md border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-100 no-underline transition-colors">
                                            Restok
                                        </a>
                                        <a href="{{ route('admin.buah.edit', $item) }}" class="inline-flex items-center justify-center rounded-md border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-100 no-underline transition-colors">
                                            Ubah
                                        </a>
                                        <form action="{{ route('admin.buah.destroy', $item) }}" method="POST" class="inline-block m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Hapus permanent buah ini dari data master?')" class="rounded-md bg-white border border-slate-200 px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-50 hover:border-rose-200 transition-colors cursor-pointer">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center text-xs font-medium text-slate-400">
                                Belum ada komoditas buah yang terdaftar.
                            </div>
                        @endforelse
                    </div>
                    
                    <div class="mt-6">
                        {{ $buah->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection