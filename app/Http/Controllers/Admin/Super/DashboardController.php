<?php

namespace App\Http\Controllers\Admin\Super;

use App\Http\Controllers\Controller;
use App\Models\Mosque;

class DashboardController extends Controller
{
    public function index()
    {
        $total = Mosque::count();
        $online = Mosque::where('is_active', true)->count();

        return view('admin.super.dashboard.index', compact('total', 'online'));
    }
}

