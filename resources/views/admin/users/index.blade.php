@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')
@section('page_title', 'Manajemen Pengguna')
@section('page_description', 'Kelola admin dan kasir dari database.')

@section('content')
<div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
                <div class="rounded-2xl bg-white border border-slate-200 p-6 shadow-sm">
                    <h2 class="text-base font-bold text-slate-900 mb-4">Tambah Pengguna Baru</h2>
                    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-slate-700">Nama</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-xl border border-slate-300 px-3 py-3 text-sm" required>
                            @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-slate-700">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-xl border border-slate-300 px-3 py-3 text-sm" required>
                            @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-slate-700">Password</label>
                                <input type="password" name="password" class="w-full rounded-xl border border-slate-300 px-3 py-3 text-sm" required>
                                @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-slate-700">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="w-full rounded-xl border border-slate-300 px-3 py-3 text-sm" required>
                            </div>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-slate-700">Role</label>
                            <select name="role" class="w-full rounded-xl border border-slate-300 px-3 py-3 text-sm" required>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="kasir" {{ old('role') === 'kasir' ? 'selected' : '' }}>Kasir</option>
                            </select>
                            @error('role')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" class="rounded-xl bg-brand-600 px-5 py-3 text-sm font-semibold text-white hover:bg-brand-700">Tambah Pengguna</button>
                    </form>
                </div>

                <div class="rounded-2xl bg-white border border-slate-200 p-6 shadow-sm">
                    <h2 class="text-base font-bold text-slate-900 mb-4">Daftar Pengguna</h2>
                    @if($users->count())
                        <div class="overflow-x-auto rounded-2xl border border-slate-300 bg-slate-50 p-4">
                            <table class="min-w-full text-left text-sm text-slate-700">
                                <thead>
                                    <tr class="border-b border-slate-200">
                                        <th class="px-4 py-3 font-semibold">Nama</th>
                                        <th class="px-4 py-3 font-semibold">Email</th>
                                        <th class="px-4 py-3 font-semibold">Role</th>
                                        <th class="px-4 py-3 font-semibold">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr class="border-b border-slate-200 last:border-0">
                                            <td class="px-4 py-4">{{ $user->name }}</td>
                                            <td class="px-4 py-4">{{ $user->email }}</td>
                                            <td class="px-4 py-4 uppercase">{{ $user->role }}</td>
                                            <td class="px-4 py-4">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition-all duration-200">Edit</a>
                                                    @if($user->id !== auth()->id())
                                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" onclick="return confirm('Hapus pengguna ini?')" class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition-all duration-200">Hapus</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="rounded-2xl border border-slate-300 bg-slate-50 p-6 text-center text-slate-700">
                            Belum ada pengguna.
                        </div>
                    @endif
                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
@endsection
