<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class KasirController extends Controller
{
    /**
     * Tampilkan dashboard/terminal kasir.
     */
    public function dashboard(): View
    {
        return view('kasir.dashboard');
    }
}
