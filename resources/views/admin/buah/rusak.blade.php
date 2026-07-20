@extends('layouts.admin')

@section('title', 'Kelola Buah Rusak')
@section('page_title', 'Kelola Buah Rusak/Busuk')
@section('page_description', 'Catat dan pantau buah yang rusak atau busuk.')

@section('content')
<div class="space-y-4">
    @forelse($buah as $item)
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm hover:border-slate-300 transition-all">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-lg bg-slate-100 overflow-hidden flex items-center justify-center shrink-0 border border-slate-200">
                    @if($item->gambar)
                        <img src="{{ asset('storage/'.$item->gambar) }}" alt="{{ $item->nama_buah }}" class="h-full w-full object-cover">
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-bold text-slate-900 text-sm truncate">{{ $item->nama_buah }}</div>
                    <div class="text-xs text-slate-500 mt-0.5">Stok saat ini: <strong class="text-slate-800">{{ $item->stok }} {{ $item->satuan }}</strong></div>
                </div>
            </div>

            <hr class="border-slate-100 my-4" />

            <form action="{{ route('admin.buah.rusak', $item) }}" method="POST" class="space-y-2">
                @csrf
                <div class="grid gap-2 md:grid-cols-[1.2fr_0.6fr_auto]">
                    <input type="text" name="alasan" value="{{ old('alasan') }}" class="w-full rounded-md border border-slate-200 px-3 py-1.5 text-xs outline-none focus:border-rose-500 transition-colors" placeholder="Alasan rusak/busuk (contoh: busuk, lembab, cacat)" required>
                    <input type="number" name="jumlah_rusak" min="1" max="{{ $item->stok }}" value="{{ old('jumlah_rusak') }}" class="w-full rounded-md border border-slate-200 px-3 py-1.5 text-xs outline-none focus:border-rose-500 transition-colors" placeholder="Kg" required>
                    <button type="submit" class="shrink-0 rounded-md bg-rose-50 border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-100 transition-colors whitespace-nowrap">
                        Laporkan Rusak
                    </button>
                </div>
            </form>
            @if($errors->has('alasan'))
                <p class="mt-1 text-[11px] text-rose-600">{{ $errors->first('alasan') }}</p>
            @endif
            @if($errors->has('jumlah_rusak'))
                <p class="mt-1 text-[11px] text-rose-600">{{ $errors->first('jumlah_rusak') }}</p>
            @endif
        </div>
    @empty
        <div class="rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center text-xs font-medium text-slate-400">
            Belum ada data buah.
        </div>
    @endforelse

    <div class="mt-6">{{ $buah->links() }}</div>
</div>
@endsection