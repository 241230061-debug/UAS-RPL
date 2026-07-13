<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta charset="utf-8" />
    <title>Dashboard Admin - Toko Buah Mas Ali</title>
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
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 bg-[#0056b3] rounded-lg text-decoration-none transition-all duration-200">
                    <span class="text-[#bbd0ff] text-base font-medium">Dashboard</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-[#e4e6f2] rounded-lg text-decoration-none text-[#434751] transition-all duration-200">
                    <span class="text-base font-medium">Data Buah</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-[#e4e6f2] rounded-lg text-decoration-none text-[#434751] transition-all duration-200">
                    <span class="text-base font-medium">Manajemen Pengguna</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-[#e4e6f2] rounded-lg text-decoration-none text-[#434751] transition-all duration-200">
                    <span class="text-base font-medium">Buah Rusak / Busuk</span>
                </a>
            </div>
        </div>

        <div class="w-full pt-4 border-t border-[#c2c6d4] box-border">
            <div class="flex items-center gap-3 px-2">
                <div class="flex w-10 h-10 shrink-0 items-center justify-center bg-[#0056b3] rounded-xl text-[#bbd0ff] font-bold shadow-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="flex flex-col overflow-hidden">
                    <div class="font-bold text-[#191c21] text-sm leading-5 truncate">{{ auth()->user()->name }}</div>
                    <div class="font-medium text-[#424752] text-xs leading-4 opacity-80">{{ ucfirst(auth()->user()->role) }}</div>
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
            <p class="m-0 font-bold text-[#191c21] text-lg">Dashboard</p>
            <span class="text-[#424752] text-xs font-medium">{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>

        <div class="flex-1 overflow-y-auto p-6 box-border">
            <div class="w-full bg-white rounded-xl border border-[#c2c6d4] p-6 box-border">
                <p class="m-0 text-[#191c21] font-bold text-base">Selamat datang, {{ auth()->user()->name }} 👋</p>
                <p class="mt-2 text-[#424752] text-sm">
                    Modul Data Buah, Manajemen Pengguna, dan Laporan Buah Rusak akan tersedia di tahap pengembangan berikutnya.
                </p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
