@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard')
@section('page_description', 'Kelola data dan pantau sistem Anda.')

@section('content')
<div class="w-full bg-white rounded-xl border border-slate-200 p-6 box-border">
    <p class="m-0 text-slate-900 font-bold text-base">Selamat datang, {{ auth()->user()->name }} 👋</p>
    <p class="mt-2 text-slate-700 text-sm">
        Gunakan menu di kiri untuk mengelola Data Buah, Manajemen Pengguna (admin dan kasir), serta Pembelian & Restok.
    </p>
</div>
@endsection
