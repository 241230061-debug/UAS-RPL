<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta charset="utf-8" />
    <title>Kelola Pembelian & Restok - Toko Buah Mas Ali</title>
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
                <a href="{{ route('admin.buah.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-[#e4e6f2] rounded-lg text-decoration-none text-[#434751] transition-all duration-200">
                    <span class="text-base font-medium">Data Buah</span>
                </a>
                <a href="{{ route('admin.restok.index') }}" class="flex items-center gap-3 px-4 py-3 bg-[#0056b3] rounded-lg text-decoration-none text-[#bbd0ff] transition-all duration-200">
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
            <div>
                <p class="m-0 font-bold text-[#191c21] text-lg">Kelola Pembelian & Restok</p>
                <p class="text-[#424752] text-sm mt-1">Tambah stok berasal dari pembelian supplier dan pantau historinya.</p>
            </div>
            <span class="text-[#424752] text-xs font-medium">{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>

        <div class="flex-1 overflow-y-auto p-6 box-border">
            @if(session('success'))
                <div class="mb-4 rounded-2xl border border-[#c2e8d1] bg-[#e9f7ef] p-4 text-sm text-[#14532d]">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
                <div class="rounded-3xl bg-white border border-[#c2c6d4] p-6 shadow-sm">
                    <h2 class="text-base font-bold text-[#191c21] mb-4">Tambah Restok / Pembelian</h2>
                    <form action="{{ route('admin.restok.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-[#424752]">Pilih Buah</label>
                            <select name="buah_id" class="w-full rounded-xl border border-[#c2c6d4] px-3 py-3 text-sm" required>
                                <option value="">Pilih buah...</option>
                                @foreach($buah as $item)
                                    <option value="{{ $item->id }}" {{ old('buah_id', $selectedBuahId ?? '') == $item->id ? 'selected' : '' }}>{{ $item->nama_buah }} ({{ $item->kategori ?: 'Tanpa kategori' }})</option>
                                @endforeach
                            </select>
                            @error('buah_id')<p class="mt-1 text-xs text-[#ba1a1a]">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-[#424752]">Supplier</label>
                            <input type="text" name="supplier" value="{{ old('supplier') }}" class="w-full rounded-xl border border-[#c2c6d4] px-3 py-3 text-sm" required>
                            @error('supplier')<p class="mt-1 text-xs text-[#ba1a1a]">{{ $message }}</p>@enderror
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-[#424752]">Jumlah</label>
                                <input type="number" name="jumlah" value="{{ old('jumlah') }}" class="w-full rounded-xl border border-[#c2c6d4] px-3 py-3 text-sm" min="1" required>
                                @error('jumlah')<p class="mt-1 text-xs text-[#ba1a1a]">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-[#424752]">Harga Beli / satuan</label>
                                <input type="number" name="harga_beli" value="{{ old('harga_beli') }}" class="w-full rounded-xl border border-[#c2c6d4] px-3 py-3 text-sm" min="0" required>
                                @error('harga_beli')<p class="mt-1 text-xs text-[#ba1a1a]">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-[#424752]">Catatan Pembelian</label>
                            <textarea name="catatan" rows="3" class="w-full rounded-xl border border-[#c2c6d4] px-3 py-3 text-sm">{{ old('catatan') }}</textarea>
                            @error('catatan')<p class="mt-1 text-xs text-[#ba1a1a]">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" class="rounded-xl bg-[#003f87] px-5 py-3 text-sm font-semibold text-white hover:bg-[#00316e]">Simpan Restok</button>
                    </form>
                </div>

                <div class="rounded-3xl bg-white border border-[#c2c6d4] p-6 shadow-sm">
                    <h2 class="text-base font-bold text-[#191c21] mb-4">Riwayat Restok</h2>
                    <div class="space-y-4">
                        @forelse($restok as $item)
                            <div class="rounded-2xl border border-[#e7e8f0] bg-[#f9f9ff] p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <div class="text-sm font-bold text-[#191c21]">{{ $item->buah->nama_buah }}</div>
                                        <div class="text-xs text-[#424752]">Supplier: {{ $item->supplier }}</div>
                                    </div>
                                    <div class="text-right text-sm text-[#424752]">{{ $item->created_at->format('d M Y') }}</div>
                                </div>
                                <div class="mt-3 grid gap-2 sm:grid-cols-2">
                                    <div class="text-sm text-[#424752]">Jumlah: {{ $item->jumlah }}</div>
                                    <div class="text-sm text-[#424752]">Total: Rp {{ number_format($item->total_biaya, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-[#e7e8f0] bg-[#f9f9ff] p-6 text-center text-[#424752]">
                                Belum ada riwayat restok.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $restok->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
