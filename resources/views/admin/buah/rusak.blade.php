@extends('layouts.admin')

@section('title', 'Kelola Buah Rusak')
@section('page_title', 'Kelola Buah Rusak/Busuk')
@section('page_description', 'Catat dan pantau buah yang rusak atau busuk.')

@section('content')
<div class="space-y-4">

    {{-- PENCARIAN --}}
    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.buah.rusak.index') }}" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[220px]">
                <label class="block mb-1.5 text-xs font-semibold text-slate-700">Cari Buah</label>
                <div class="relative">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17.5 10.5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, kode, atau kategori buah..." class="w-full rounded-lg border border-slate-200 pl-9 pr-3 py-2 text-sm outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition-colors">
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="rounded-lg bg-rose-600 px-5 py-2 text-sm font-semibold text-white hover:bg-rose-700 border-0 cursor-pointer transition-colors">Cari</button>
                @if($search)
                    <a href="{{ route('admin.buah.rusak.index') }}" class="rounded-lg bg-slate-100 px-5 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200 no-underline transition-colors flex items-center">Reset</a>
                @endif
            </div>
        </form>

        @if($search)
            <p class="mt-3 text-xs text-slate-500">
                Menampilkan <strong class="text-slate-700">{{ $buah->total() }}</strong> hasil untuk "<strong class="text-slate-700">{{ $search }}</strong>"
            </p>
        @endif
    </div>

    {{-- DAFTAR BUAH --}}
    @forelse($buah as $item)
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:border-slate-300 transition-all">
            <div class="flex flex-col md:flex-row md:items-center gap-4">

                {{-- Info buah --}}
                <div class="flex items-center gap-3 md:w-64 shrink-0">
                    <div class="w-12 h-12 rounded-lg bg-slate-100 overflow-hidden flex items-center justify-center shrink-0 border border-slate-200">
                        @if($item->gambar)
                            <img src="{{ asset('storage/'.$item->gambar) }}" alt="{{ $item->nama_buah }}" class="h-full w-full object-cover">
                        @else
                            <span class="text-slate-300 text-[10px]">N/A</span>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <div class="font-bold text-slate-900 text-sm truncate">{{ $item->nama_buah }}</div>
                        <div class="text-xs text-slate-500 mt-0.5">
                            Stok: <strong class="text-slate-800">{{ $item->stok }} {{ $item->satuan }}</strong>
                        </div>
                    </div>
                </div>

                {{-- Garis pemisah vertikal (desktop) --}}
                <div class="hidden md:block h-10 w-px bg-slate-100"></div>

                {{-- Form laporan --}}
                <form action="{{ route('admin.buah.rusak', $item) }}" method="POST" class="flex-1">
                    @csrf
                    <div class="grid gap-2 md:grid-cols-[1fr_120px_auto]">
                        <div>
                            <input type="text" name="alasan" value="{{ old('alasan') }}" class="w-full rounded-md border border-slate-200 px-3 py-2 text-xs outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition-colors" placeholder="Alasan (contoh: busuk, lembab, cacat)" required>
                            @error('alasan')
                                <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <input type="number" name="jumlah_rusak" min="1" max="{{ $item->stok }}" value="{{ old('jumlah_rusak') }}" class="w-full rounded-md border border-slate-200 px-3 py-2 text-xs outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition-colors" placeholder="Jumlah ({{ $item->satuan }})" required>
                            @error('jumlah_rusak')
                                <p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="shrink-0 rounded-md bg-rose-50 border border-rose-200 px-4 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-100 active:bg-rose-200 transition-colors whitespace-nowrap">
                            Laporkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @empty
        <div class="rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center text-xs font-medium text-slate-400">
            @if($search)
                Tidak ada buah yang cocok dengan pencarian "{{ $search }}".
            @else
                Belum ada data buah.
            @endif
        </div>
    @endforelse

    <div class="mt-6">{{ $buah->links() }}</div>
</div>
@endsection
