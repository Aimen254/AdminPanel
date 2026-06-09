<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\ImportRecord;
use App\Models\User;

class ManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|manager']);
    }

    public function index()
    {
        $stats = [
            'total_records' => ImportRecord::count(),
            'active_records' => ImportRecord::where('status', 'active')->count(),
            'total_users'   => User::role('user')->count(),
            'recent_records' => ImportRecord::with('importer')->latest()->take(10)->get(),
        ];

        return view('manager.dashboard', compact('stats'));
    }
}
