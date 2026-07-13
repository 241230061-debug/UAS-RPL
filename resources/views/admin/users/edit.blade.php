<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta charset="utf-8" />
    <title>Edit Pengguna - Toko Buah Mas Ali</title>
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
                <a href="{{ route('admin.restok.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-[#e4e6f2] rounded-lg text-decoration-none text-[#434751] transition-all duration-200">
                    <span class="text-base font-medium">Pembelian & Restok</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 bg-[#0056b3] rounded-lg text-decoration-none text-[#bbd0ff] transition-all duration-200">
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
                <p class="m-0 font-bold text-[#191c21] text-lg">Edit Pengguna</p>
                <p class="text-[#424752] text-sm mt-1">Perbarui data pengguna.</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-[#c2c6d4] bg-white px-4 py-2 text-sm font-semibold text-[#424752] hover:bg-[#e4e6f2] transition-all duration-200">
                Kembali ke Manajemen Pengguna
            </a>
        </div>

        <div class="flex-1 overflow-y-auto p-6 box-border">
            <div class="rounded-3xl bg-white border border-[#c2c6d4] p-6 shadow-sm max-w-3xl mx-auto">
                <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block mb-2 text-sm font-semibold text-[#424752]">Nama</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-xl border border-[#c2c6d4] px-3 py-3 text-sm" required>
                        @error('name')<p class="mt-1 text-xs text-[#ba1a1a]">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-[#424752]">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-xl border border-[#c2c6d4] px-3 py-3 text-sm" required>
                        @error('email')<p class="mt-1 text-xs text-[#ba1a1a]">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-[#424752]">Password Baru</label>
                            <input type="password" name="password" class="w-full rounded-xl border border-[#c2c6d4] px-3 py-3 text-sm">
                            @error('password')<p class="mt-1 text-xs text-[#ba1a1a]">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-[#424752]">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="w-full rounded-xl border border-[#c2c6d4] px-3 py-3 text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-[#424752]">Role</label>
                        <select name="role" class="w-full rounded-xl border border-[#c2c6d4] px-3 py-3 text-sm" required>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="kasir" {{ old('role', $user->role) === 'kasir' ? 'selected' : '' }}>Kasir</option>
                        </select>
                        @error('role')<p class="mt-1 text-xs text-[#ba1a1a]">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="rounded-xl bg-[#003f87] px-5 py-3 text-sm font-semibold text-white hover:bg-[#00316e]">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
