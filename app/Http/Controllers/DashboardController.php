<?php

namespace App\Http\Controllers;

use App\Models\Sos;
use Inertia\Inertia;
use App\Models\Patrol;
use App\Models\PatrolMedia;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $patrolCurrentYearCount = Patrol::whereYear('created_at', now()->format('Y'))
            ->count();

        $latestPatrol = Patrol::with('company:id,name', 'patrolLocation:id,name', 'patrolCheckpoint:id,name', 'patrolEvent:id,name', 'patrolUser:id,name', 'mediaItems:id,filename,patrol_id')
            ->withCount('comments')
            ->latest()
            ->first();

        $checkpointCount = Patrol::whereYear('created_at', now()->format('Y'))
            ->distinct('patrol_checkpoint_id')
            ->count('patrol_checkpoint_id');

        $monthlyCount = Patrol::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('created_at', now()->format('Y'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('count', 'month');

        $incidentCount = Sos::whereYear('created_at', now()->format('Y'))
            ->count();

        // Initialize an array with all months (1-12) to ensure each month has a value
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[$i] = $monthlyCount->get($i, 0); // Get count for the month, default to 0 if not found
        }
        // dd($monthlyData);

        return Inertia::render('Dashboard/Index', [
            'title' => "Dashboard",
            'patrolCurrentYearCount' => $patrolCurrentYearCount,
            'latestPatrol' => $latestPatrol,
            'checkpointCount' => $checkpointCount,
            'monthlyData' => $monthlyData,
            'incidentCount' => $incidentCount,
        ]);
    }
}
