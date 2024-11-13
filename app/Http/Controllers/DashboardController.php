<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Patrol;
use App\Models\Company;
use App\Models\PatrolUser;
use App\Models\PatrolLocation;
use App\Models\PatrolCheckpoint;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $role = User::with('role:id,name')
            ->where('id', Auth::id())
            ->first();

        $companyCount = Company::where('is_active', 1)->count();

        $locationCount = PatrolLocation::where('is_active', 1)->count();

        $checkpointCount = PatrolCheckpoint::where('is_active', 1)->count();

        $userMobileCount = PatrolUser::where('is_active', 1)->count();

        $userWebCount = User::where('is_active', 1)->count();

        $patrolTransactionCount = Patrol::count();

        $latestLocations = PatrolLocation::where('is_active', 1)
            ->with('company:id,name')
            ->latest('created_at')
            ->limit(10)
            ->get();

        return Inertia::render('Dashboard/Index', [
            'title' => "Dashboard",
            'companyCount' => $companyCount,
            'locationCount' => $locationCount,
            'checkpointCount' => $checkpointCount,
            'userMobileCount' => $userMobileCount,
            'userWebCount' => $userWebCount,
            'patrolTransactionCount' => $patrolTransactionCount,
            'latestLocations' => $latestLocations->toArray(),
            'role' => $role
        ]);
    }
}
