<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buah;
use App\Models\Restok;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RestokController extends Controller
{
    public function index(Request $request): View
    {
        $selectedBuahId = $request->query('buah_id');
        $buah = Buah::where('aktif', true)
            ->orderBy('nama_buah')
            ->get();

        $restok = Restok::with('buah', 'user')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.restok.index', compact('buah', 'restok', 'selectedBuahId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'buah_id' => ['required', 'integer', 'exists:buah,id'],
            'supplier' => ['required', 'string', 'max:255'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'harga_beli' => ['required', 'integer', 'min:0'],
            'catatan' => ['nullable', 'string'],
        ]);

        $buah = Buah::findOrFail($validated['buah_id']);
        $validated['user_id'] = auth()->id();
        $validated['total_biaya'] = $validated['jumlah'] * $validated['harga_beli'];

        Restok::create($validated);

        $buah->increment('stok', $validated['jumlah']);

        return redirect()->route('admin.restok.index')->with('success', 'Restok berhasil ditambahkan.');
    }
}
