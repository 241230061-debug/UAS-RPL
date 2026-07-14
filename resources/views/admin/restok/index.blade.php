<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta charset="utf-8" />
    <title>Kelola Pembelian & Restok - Toko Buah Mas Ali</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0f4fa',
                            100: '#dbe5f5',
                            500: '#0056b3',
                            600: '#003f87',
                            700: '#002d66',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="m-0 p-0 bg-slate-50 font-sans antialiased text-slate-800">
<div class="flex h-screen w-full items-start overflow-hidden relative">
    
    <!-- SIDEBAR -->
    <div class="flex flex-col w-[260px] h-full items-start justify-between px-4 py-6 bg-slate-100 border-r border-slate-200 box-border shrink-0 hidden md:flex">
        <div class="w-full flex flex-col items-start">
            <div class="px-2 w-full box-border">
                <p class="m-0 font-bold text-brand-600 text-xl xl:text-2xl leading-tight">
                    Toko Buah Mas Ali 
                    <span class="text-xs font-semibold text-slate-500 block uppercase tracking-wider mt-1">Panel Admin</span>
                </p>
            </div>
            <div class="mt-8 w-full flex flex-col gap-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-200/70 rounded-xl text-slate-600 no-underline font-medium transition-all duration-200">
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.buah.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-200/70 rounded-xl text-slate-600 no-underline font-medium transition-all duration-200">
                    <span>Data Buah</span>
                </a>
                <a href="{{ route('admin.restok.index') }}" class="flex items-center gap-3 px-4 py-3 bg-brand-500 rounded-xl text-white no-underline font-semibold shadow-sm shadow-brand-500/20 transition-all duration-200">
                    <span>Pembelian & Restok</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-200/70 rounded-xl text-slate-600 no-underline font-medium transition-all duration-200">
                    <span>Manajemen Pengguna</span>
                </a>
            </div>
        </div>
        
        <!-- USER PROFILE & LOGOUT -->
        <div class="w-full pt-4 border-t border-slate-200 box-border">
            <div class="flex items-center gap-3 px-2 mb-3">
                <div class="flex w-10 h-10 shrink-0 items-center justify-center bg-brand-500 rounded-xl text-white font-bold shadow-sm">
                    {{ strtoupper(substr(auth()->user()->name ?? 'Ad', 0, 2)) }}
                </div>
                <div class="flex flex-col overflow-hidden">
                    <div class="font-bold text-slate-900 text-sm leading-tight truncate">{{ auth()->user()->name ?? 'Admin' }}</div>
                    <div class="font-medium text-slate-500 text-xs leading-none mt-1">{{ ucfirst(auth()->user()->role ?? 'Admin') }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2.5 text-red-600 text-sm font-semibold rounded-xl hover:bg-red-50 border-0 bg-transparent cursor-pointer transition-colors duration-150">
                    Keluar
                </button>
            </form>
        </div>
    </div>

    <!-- MAIN CONTENT CONTAINER -->
    <div class="flex flex-col flex-1 h-full bg-slate-50 overflow-hidden">
        
        <!-- TOPBAR -->
        <div class="flex h-16 items-center justify-between px-6 bg-white border-b border-slate-200 shrink-0 box-border">
            <div>
                <h1 class="m-0 font-bold text-slate-900 text-lg leading-tight">Kelola Pembelian & Restok</h1>
                <p class="text-slate-500 text-xs mt-0.5 hidden sm:block">Tambah stok dari supplier dan pantau riwayat transaksi Anda.</p>
            </div>
            <span class="text-slate-500 text-xs font-semibold bg-slate-100 px-3 py-1.5 rounded-full">{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>

        <!-- CONTENT BODY -->
        <div class="flex-1 overflow-y-auto p-6 box-border">
            @if(session('success'))
                <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 shadow-sm flex items-center gap-2">
                    <span class="font-semibold">Berhasil:</span> {{ session('success') }}
                </div>
            @endif

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
                                <label class="block mb-1.5 text-sm font-semibold text-slate-700">Jumlah (Qty)</label>
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
                                    <div class="text-xs text-slate-500">Jumlah: <span class="font-bold text-slate-800">{{ $item->jumlah }} pcs</span></div>
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
    </div>
</div>
</body>
</html>