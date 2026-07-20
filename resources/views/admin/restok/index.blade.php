@extends('layouts.admin')

@section('title', 'Kelola Pembelian & Restok')
@section('page_title', 'Kelola Pembelian & Restok')
@section('page_description', 'Tambah stok dari supplier dan pantau riwayat transaksi Anda.')

@section('content')
<div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr] items-start">
                
                <!-- FORM CARD -->
                <div class="rounded-2xl bg-white border border-slate-200 p-6 shadow-sm">
                    <h2 class="text-base font-bold text-slate-900 mb-5 pb-3 border-b border-slate-100">Tambah Restok / Pembelian</h2>
                    <form action="{{ route('admin.restok.store') }}" method="POST" class="space-y-4 m-0">
                        @csrf
                        <div>
                            <label class="block mb-1.5 text-sm font-semibold text-slate-700">Pilih Buah</label>
                            <select name="buah_id" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100 transition-all box-border" required>
                                <option value="">Pilih buah...</option>
                                @foreach($buah as $item)
                                    <option value="{{ $item->id }}" {{ old('buah_id', $selectedBuahId ?? '') == $item->id ? 'selected' : '' }}>{{ $item->nama_buah }} ({{ $item->kategori ?: 'Tanpa kategori' }})</option>
                                @endforeach
                            </select>
                            @error('buah_id')<p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                        </div>
                        
                        <div>
                            <label class="block mb-1.5 text-sm font-semibold text-slate-700">Supplier</label>
                            <input type="text" name="supplier" value="{{ old('supplier') }}" placeholder="Nama PT atau distributor" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm text-slate-800 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100 transition-all box-border" required>
                            @error('supplier')<p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                        </div>
                        
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="block mb-1.5 text-sm font-semibold text-slate-700">Jumlah (Kg)</label>
                                <input type="number" name="jumlah" value="{{ old('jumlah') }}" placeholder="0" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm text-slate-800 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100 transition-all box-border" min="1" required>
                                @error('jumlah')<p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block mb-1.5 text-sm font-semibold text-slate-700">Harga Beli / Satuan</label>
                                <div class="relative flex items-center">
                                    <span class="absolute left-3 text-sm text-slate-400 font-medium">Rp</span>
                                    <input type="number" name="harga_beli" value="{{ old('harga_beli') }}" placeholder="0" class="w-full rounded-xl border border-slate-300 pl-9 pr-3 py-2.5 text-sm text-slate-800 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100 transition-all box-border" min="0" required>
                                </div>
                                @error('harga_beli')<p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        
                        <div>
                            <label class="block mb-1.5 text-sm font-semibold text-slate-700">Catatan Pembelian <span class="text-xs font-normal text-slate-400">(Opsional)</span></label>
                            <textarea name="catatan" rows="3" placeholder="Tambahkan info detail (misal: kualitas buah, metode pembayaran)" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm text-slate-800 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100 transition-all box-border">{{ old('catatan') }}</textarea>
                            @error('catatan')<p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>@enderror
                        </div>
                        
                        <div class="pt-2">
                            <button type="submit" class="w-full sm:w-auto rounded-xl bg-brand-600 px-6 py-3 text-sm font-bold text-white hover:bg-brand-700 active:bg-brand-700 border-0 cursor-pointer transition-colors shadow-sm shadow-brand-600/10">Simpan Restok</button>
                        </div>
                    </form>
                </div>

                <!-- HISTORY CARD -->
                <div class="rounded-2xl bg-white border border-slate-200 p-6 shadow-sm">
                    <h2 class="text-base font-bold text-slate-900 mb-5 pb-3 border-b border-slate-100">Riwayat Restok</h2>
                    <div class="space-y-3">
                        @forelse($restok as $item)
                            <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-4 hover:bg-slate-50 transition-colors">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <div class="text-sm font-bold text-slate-900">{{ $item->buah->nama_buah }}</div>
                                        <div class="text-xs text-slate-500 mt-0.5">Supplier: <span class="font-medium text-slate-700">{{ $item->supplier }}</span></div>
                                    </div>
                                    <div class="text-right text-xs font-semibold text-slate-400 bg-white px-2.5 py-1 rounded-md border border-slate-200">{{ $item->created_at->format('d M Y') }}</div>
                                </div>
                                <div class="mt-4 flex items-center justify-between pt-3 border-t border-dashed border-slate-200">
                                    <div class="text-xs text-slate-500">Jumlah: <span class="font-bold text-slate-800">{{ $item->jumlah }} Kg</span></div>
                                    <div class="text-sm font-extrabold text-brand-600">Rp {{ number_format($item->total_biaya, 0, ',', '.') }}</div>
                                </div>
                                @if($item->catatan)
                                    <div class="mt-2 text-xs text-slate-400 italic bg-white p-2 rounded-lg border border-slate-100">
                                        * {{ $item->catatan }}
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50/30 p-8 text-center text-sm text-slate-400 font-medium">
                                Belum ada riwayat restok terdaftar.
                            </div>
                        @endforelse
                    </div>

                    @if($restok->hasPages())
                        <div class="mt-6 pt-4 border-t border-slate-100">
                            {{ $restok->links() }}
                        </div>
                    @endif
                </div>
                
            </div>
        </div>
@endsection