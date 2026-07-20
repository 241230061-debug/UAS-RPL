<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BuahController extends Controller
{
    /**
     * Tampilkan daftar buah dan form input.
     */
    public function index(Request $request): View
    {
        $lowStockThreshold = 5;

        $buah = Buah::orderBy('nama_buah')
            ->paginate(12);

        $lowStockCount = Buah::where('stok', '>', 0)
            ->where('stok', '<=', $lowStockThreshold)
            ->count();

        return view('admin.buah.index', compact('buah', 'lowStockCount', 'lowStockThreshold'));
    }

    /**
     * Simpan buah baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:50', 'unique:buah,kode'],
            'nama_buah' => ['required', 'string', 'max:255'],
            'kategori' => ['nullable', 'string', 'max:100'],
            'harga' => ['required', 'integer', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
            'satuan' => ['required', 'string', 'max:50'],
            'gambar' => ['nullable', 'image', 'max:2048'],
            'keterangan' => ['nullable', 'string'],
            'aktif' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('buah', 'public');
        }

        $validated['aktif'] = $request->boolean('aktif', true);

        Buah::create($validated);

        return redirect()->route('admin.buah.index')->with('success', 'Data buah berhasil disimpan.');
    }

    /**
     * Tampilkan halaman kelola buah rusak/busuk.
     */
    public function rusakIndex(): View
    {
        $buah = Buah::orderBy('nama_buah')->paginate(12);

        return view('admin.buah.rusak', compact('buah'));
    }

    /**
     * Laporkan stok buah rusak/busuk dan kurangi stok.
     */
    public function reportRusak(Request $request, Buah $buah): RedirectResponse
    {
        $validated = $request->validate([
            'jumlah_rusak' => ['required', 'integer', 'min:1'],
        ]);

        if ($validated['jumlah_rusak'] > $buah->stok) {
            return redirect()->route('admin.buah.rusak.index')
                ->withErrors(['jumlah_rusak' => 'Jumlah rusak tidak boleh lebih besar dari stok saat ini.'])
                ->withInput();
        }

        $buah->decrement('stok', $validated['jumlah_rusak']);

        return redirect()->route('admin.buah.rusak.index')->with('success', 'Stok buah rusak berhasil dicatat.');
    }

    /**
     * Tampilkan form edit buah.
     */
    public function edit(Buah $buah): View
    {
        return view('admin.buah.edit', compact('buah'));
    }

    /**
     * Perbarui buah yang ada.
     */
    public function update(Request $request, Buah $buah): RedirectResponse
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:50', 'unique:buah,kode,' . $buah->id],
            'nama_buah' => ['required', 'string', 'max:255'],
            'kategori' => ['nullable', 'string', 'max:100'],
            'harga' => ['required', 'integer', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
            'satuan' => ['required', 'string', 'max:50'],
            'gambar' => ['nullable', 'image', 'max:2048'],
            'keterangan' => ['nullable', 'string'],
            'aktif' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('buah', 'public');
        }

        $validated['aktif'] = $request->boolean('aktif', true);

        $buah->update($validated);

        return redirect()->route('admin.buah.index')->with('success', 'Data buah berhasil diperbarui.');
    }

    /**
     * Hapus buah.
     */
    public function destroy(Buah $buah): RedirectResponse
    {
        $buah->delete();

        return redirect()->route('admin.buah.index')->with('success', 'Data buah berhasil dihapus.');
    }
}