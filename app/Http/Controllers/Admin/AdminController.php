<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Tampilkan dashboard admin.
     */
    public function dashboard(): View
    {
        return view('admin.dashboard');
    }
}
