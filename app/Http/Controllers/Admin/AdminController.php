<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImportRecord;
use App\Models\User;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $stats = [
            'total_users'    => User::count(),
            'admin_count'    => User::role('admin')->count(),
            'manager_count'  => User::role('manager')->count(),
            'user_count'     => User::role('user')->count(),
            'total_imports'  => ImportRecord::count(),
            'recent_imports' => ImportRecord::with('importer')->latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
