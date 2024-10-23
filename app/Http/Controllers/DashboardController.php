<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';

        $role = User::with('role:id,name')
            ->where('id', Auth::id())
            ->first();

        return Inertia::render('Dashboard/Index', [
            'title' => $title,
            'role' => $role
        ]);
    }
}
