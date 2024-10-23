<?php

namespace App\Http\Controllers;

use App\Models\Patrol;
use Illuminate\Http\Request;

class PatrolController extends Controller
{
    public function index()
    {
        $title = 'Operation';

        $get = Patrol::with('company:id,name', 'patrolLocation:id,name', 'patrolCheckpoint:id,name', 'patrolEvent:id,name', 'patrolUser:id,name', 'media')
            ->withCount('comment')
            ->withCount('media')
            ->whereDate('created_at', now())
            ->paginate(200);

        return view('patrol.index', [
            'title' => $title,
            'get' => $get
        ]);
    }
}
