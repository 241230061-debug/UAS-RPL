<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta charset="utf-8" />
    <title>Data Master - Toko Buah Mas Ali</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            corePlugins: { preflight: false },
            theme: { extend: {} },
            plugins: [],
        }
    </script>
</head>
<body class="m-0 p-0 bg-[#f9f9ff] font-sans antialiased">
<div class="flex h-screen w-full items-start overflow-hidden relative">
    <div class="flex flex-col w-[260px] h-full items-start justify-between px-4 py-6 bg-[#f2f3fc] border-r border-[#c2c6d4] box-border shrink-0">
        <div class="w-full flex flex-col items-start">
            <div class="px-2 w-full box-border">
                <p class="m-0 font-bold text-[#003f87] text-xl xl:text-2xl leading-8">
                    Toko Buah Mas Ali <span class="text-sm font-medium text-[#424752] block opacity-70">Panel Admin</span>
                </p>
            </div>
            <div class="mt-8 w-full flex flex-col gap-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-[#e4e6f2] rounded-lg text-decoration-none text-[#434751] transition-all duration-200">
                    <span class="text-base font-medium">Dashboard</span>
                </a>
                <a href="{{ route('admin.buah.index') }}" class="flex items-center gap-3 px-4 py-3 bg-[#0056b3] rounded-lg text-decoration-none text-[#bbd0ff] transition-all duration-200">
                    <span class="text-base font-medium">Data Master</span>
                </a>
                <a href="{{ route('admin.restok.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-[#e4e6f2] rounded-lg text-decoration-none text-[#434751] transition-all duration-200">
                    <span class="text-base font-medium">Pembelian & Restok</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-[#e4e6f2] rounded-lg text-decoration-none text-[#434751] transition-all duration-200">
                    <span class="text-base font-medium">Manajemen Pengguna</span>
                </a>
            </div>
        </div>
        <div class="w-full pt-4 border-t border-[#c2c6d4] box-border">
            <div class="flex items-center gap-3 px-2">
                <div class="flex w-10 h-10 shrink-0 items-center justify-center bg-[#0056b3] rounded-xl text-[#bbd0ff] font-bold shadow-sm">
                    {{ strtoupper(substr(auth()->user()->name ?? 'Ad', 0, 2)) }}
                </div>
                <div class="flex flex-col overflow-hidden">
                    <div class="font-bold text-[#191c21] text-sm leading-5 truncate">{{ auth()->user()->name ?? 'Admin' }}</div>
                    <div class="font-medium text-[#424752] text-xs leading-4 opacity-80">{{ ucfirst(auth()->user()->role ?? 'Admin') }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="w-full text-left px-2 py-2 text-[#ba1a1a] text-sm font-semibold rounded-lg hover:bg-[#fdecec] border-0 bg-transparent cursor-pointer">
                    Keluar
                </button>
            </form>
        </div>
    </div>

    <div class="flex flex-col flex-1 h-full bg-[#f9f9ff] overflow-hidden">
        <div class="flex h-16 items-center justify-between px-6 bg-[#f9f9ff] border-b border-[#c2c6d4] shrink-0 box-border">
            <p class="m-0 font-bold text-[#191c21] text-lg">Data Master</p>
            <span class="text-[#424752] text-xs font-medium">{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>

        <div class="flex-1 overflow-y-auto p-6 box-border">
            @if(session('success'))
                <div class="mb-4 rounded-2xl border border-[#c2e8d1] bg-[#e9f7ef] p-4 text-sm text-[#14532d]">
                    {{ session('success') }}
                </div>
            @endif

            @if(!empty($lowStockCount) && $lowStockCount > 0)
                <div class="mb-4 rounded-2xl border border-[#f5d1d1] bg-[#fff1f0] p-4 text-sm text-[#842029]">
                    <strong>Peringatan:</strong> Ada {{ $lowStockCount }} buah dengan stok menipis (≤ {{ $lowStockThreshold }}).
                    <span class="block mt-2">Silakan tambahkan restok segera untuk mencegah kehabisan stok.</span>
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-[1.4fr_1fr]">
                <div class="space-y-4">
                    <div class="rounded-3xl bg-white border border-[#c2c6d4] p-6 shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                            <div>
                                <p class="text-base font-bold text-[#191c21]">Tambah Data Buah</p>
                                <p class="text-sm text-[#424752] mt-1">Tambahkan buah baru ke dalam data master.</p>
                            </div>
                        </div>

                        <form action="{{ route('admin.buah.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-[#424752]">Kode</label>
                                <input type="text" name="kode" value="{{ old('kode') }}" class="w-full rounded-xl border border-[#c2c6d4] px-3 py-3 text-sm" required>
                                @error('kode')<p class="mt-1 text-xs text-[#ba1a1a]">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-[#424752]">Nama Buah</label>
                                <input type="text" name="nama_buah" value="{{ old('nama_buah') }}" class="w-full rounded-xl border border-[#c2c6d4] px-3 py-3 text-sm" required>
                                @error('nama_buah')<p class="mt-1 text-xs text-[#ba1a1a]">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-[#424752]">Kategori</label>
                                <input type="text" name="kategori" value="{{ old('kategori') }}" class="w-full rounded-xl border border-[#c2c6d4] px-3 py-3 text-sm">
                                @error('kategori')<p class="mt-1 text-xs text-[#ba1a1a]">{{ $message }}</p>@enderror
                            </div>
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label class="block mb-2 text-sm font-semibold text-[#424752]">Harga</label>
                                    <input type="number" name="harga" value="{{ old('harga') }}" class="w-full rounded-xl border border-[#c2c6d4] px-3 py-3 text-sm" min="0" required>
                                    @error('harga')<p class="mt-1 text-xs text-[#ba1a1a]">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-semibold text-[#424752]">Stok</label>
                                    <input type="number" name="stok" value="{{ old('stok', 0) }}" class="w-full rounded-xl border border-[#c2c6d4] px-3 py-3 text-sm" min="0" required>
                                    @error('stok')<p class="mt-1 text-xs text-[#ba1a1a]">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-[#424752]">Satuan</label>
                                <input type="text" name="satuan" value="{{ old('satuan', 'kg') }}" class="w-full rounded-xl border border-[#c2c6d4] px-3 py-3 text-sm" required>
                                @error('satuan')<p class="mt-1 text-xs text-[#ba1a1a]">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-[#424752]">Gambar</label>
                                <input type="file" name="gambar" class="w-full rounded-xl border border-[#c2c6d4] px-3 py-2 text-sm">
                                @error('gambar')<p class="mt-1 text-xs text-[#ba1a1a]">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-[#424752]">Keterangan</label>
                                <textarea name="keterangan" rows="3" class="w-full rounded-xl border border-[#c2c6d4] px-3 py-3 text-sm">{{ old('keterangan') }}</textarea>
                                @error('keterangan')<p class="mt-1 text-xs text-[#ba1a1a]">{{ $message }}</p>@enderror
                            </div>
                            <div class="flex items-center gap-3">
                                <label class="inline-flex items-center gap-2 text-sm font-semibold text-[#424752]">
                                    <input type="checkbox" name="aktif" value="1" {{ old('aktif', true) ? 'checked' : '' }}>
                                    Aktif
                                </label>
                                <button type="submit" class="rounded-xl bg-[#003f87] px-4 py-3 text-sm font-semibold text-white hover:bg-[#00316e]">Simpan Buah</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="rounded-3xl bg-white border border-[#c2c6d4] p-6 shadow-sm">
                    <h2 class="text-base font-bold text-[#191c21] mb-4">Daftar Buah</h2>
                    <div class="space-y-4">
                        @forelse($buah as $item)
                            <div class="rounded-2xl border border-[#e7e8f0] bg-[#f9f9ff] p-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-16 h-16 rounded-2xl bg-[#e7e8f0] overflow-hidden flex items-center justify-center">
                                        @if($item->gambar)
                                            <img src="{{ asset('storage/'.$item->gambar) }}" alt="{{ $item->nama_buah }}" class="h-full w-full object-cover">
                                        @else
                                            <span class="text-2xl">🍎</span>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-bold text-[#191c21]">{{ $item->nama_buah }}</div>
                                        <div class="text-sm text-[#424752] mt-1">{{ $item->kategori ?: 'Tanpa kategori' }}</div>
                                        <div class="text-sm text-[#424752]">Rp {{ number_format($item->harga, 0, ',', '.') }} / {{ $item->satuan }}</div>
                                        <div class="flex flex-wrap items-center gap-2 mt-1 text-sm text-[#424752]">
                                            <span>Stok: {{ $item->stok }}</span>
                                            @if($item->stok <= $lowStockThreshold)
                                                <span class="rounded-full bg-[#f8d7da] px-2 py-1 text-xs font-semibold text-[#842029]">Stok menipis</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 grid gap-3 lg:grid-cols-[1fr_auto] items-end">
                                    <form action="{{ route('admin.buah.rusak', $item) }}" method="POST" class="grid gap-3">
                                        @csrf
                                        <label class="text-sm font-semibold text-[#424752]">Jumlah Rusak / Busuk</label>
                                        <input type="number" name="jumlah_rusak" min="1" max="{{ $item->stok }}" value="{{ old('jumlah_rusak') }}" class="w-full rounded-xl border border-[#c2c6d4] px-3 py-3 text-sm" placeholder="Masukkan jumlah rusak" required>
                                        @if($errors->has('jumlah_rusak'))
                                            <p class="text-xs text-[#ba1a1a]">{{ $errors->first('jumlah_rusak') }}</p>
                                        @endif
                                        <button type="submit" class="rounded-xl bg-[#ba1a1a] px-4 py-3 text-sm font-semibold text-white hover:bg-[#981515] transition-all duration-200">
                                            Laporkan Rusak
                                        </button>
                                    </form>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a href="{{ route('admin.restok.index', ['buah_id' => $item->id]) }}" class="inline-flex items-center gap-2 rounded-xl border border-[#c2c6d4] bg-[#eef7ff] px-4 py-2 text-sm font-semibold text-[#0f4d90] hover:bg-[#dbeeff] transition-all duration-200">
                                            Restok
                                        </a>
                                        <a href="{{ route('admin.buah.edit', $item) }}" class="inline-flex items-center gap-2 rounded-xl border border-[#c2c6d4] bg-white px-4 py-2 text-sm font-semibold text-[#424752] hover:bg-[#e4e6f2] transition-all duration-200">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.buah.destroy', $item) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Hapus buah ini?')" class="rounded-xl bg-[#ba1a1a] px-4 py-2 text-sm font-semibold text-white hover:bg-[#981515] transition-all duration-200">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-[#e7e8f0] bg-[#f9f9ff] p-6 text-center text-[#424752]">
                                Belum ada data buah.
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
</body>
</html>
