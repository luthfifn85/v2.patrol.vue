<?php

namespace App\Http\Controllers;

use Throwable;
use Inertia\Inertia;
use App\Models\Patrol;
use Illuminate\Support\Facades\Log;

class PatrolController extends Controller
{
    public function index()
    {
        $patrols = Patrol::with('company:id,name', 'patrolLocation:id,name', 'patrolCheckpoint:id,name', 'patrolEvent:id,name', 'patrolUser:id,name', 'mediaItems')
            ->withCount('comments')
            ->withCount('mediaItems')
            ->whereDate('created_at', now())
            ->get();

        return Inertia::render('Patrol/Index', [
            'title' => "Patrols",
            'patrols' => $patrols->toArray(),
            'patrolCount' => $patrols->count()
        ]);
    }

    public function show(Patrol $patrolBind)
    {
        try {
            $patrol = $patrolBind->load([
                'company:id,name',
                'patrolLocation:id,name',
                'patrolCheckpoint:id,name',
                'patrolEvent:id,name',
                'patrolUser:id,name',
                'mediaItems',
                'comments' => function ($query) {
                    $query->with([
                        'patrolUser' => function ($query) {
                            $query->with('patrolRole:id,name');
                        }
                    ]);
                },
            ])
                ->loadCount('comments')
                ->loadCount('mediaItems');


            return Inertia::render('Patrol/Show', [
                'title' => "Patrol",
                'patrol' => $patrol->toArray()
            ]);
        } catch (Throwable $th) {
            dd($th);
            Log::error($th);
            return redirect()->back()->with('error', [
                'message' => 'Something went wrong',
                'id' => uniqid()
            ]);
        }
    }
}
