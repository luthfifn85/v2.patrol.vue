<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Models\Patrol;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        try {
            // role: admin & client
            if (Auth::user()->patrol_role_id == 1 || Auth::user()->patrol_role_id == 4) {
                $get = Patrol::with('patrolLocation:id,name', 'patro.Checkpoint:id,name', 'patrolEvent:id,name', 'patrolUser:id,name')
                    ->where('company_id', Auth::user()->company_id)
                    ->whereDate('created_at', now())
                    ->latest()
                    ->get();

                $show = [];

                foreach ($get as $data) {
                    $media = [];

                    foreach ($data->mediaItems as $list) {
                        $media[] = [
                            'id' => $list->id,
                            'name' => $list->filename
                        ];
                    }

                    $show[] = [
                        'id' => $data->id,
                        'location' => $data->patrolLocation->name,
                        'checkpoint' => $data->patrolCheckpoint->name,
                        'event' => $data->event->name,
                        'note' => $data->note,
                        'latitude' => $data->latitude,
                        'longitude' => $data->longitude,
                        'created_at' => $data->created_at->diffForHumans(),
                        'created_by' => $data->patrolUser->name,
                        'views' => $data->views,
                        'comments' => $data->comments->count(),
                        'media' => [
                            'total' => $data->mediaItems->count(),
                            'image' => $media
                        ]
                    ];
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'welcome home',
                    'total' => $get->count(),
                    'data' => $show
                ], 200);
            }

            // role: chief & guard
            else {
                $get = Patrol::with('patrolLocation:id,name', 'patrolCheckpoint:id,name', 'patrolEvent:id,name', 'patrolUser:id,name')
                    ->where('patrol_location_id', Auth::user()->patrol_location_id)
                    ->whereDate('created_at', now())
                    ->latest()
                    ->get();

                $show = [];

                foreach ($get as $data) {
                    $media = [];

                    foreach ($data->mediaItems as $list) {
                        $media[] = [
                            'id' => $list->id,
                            'name' => $list->filename
                        ];
                    }

                    $show[] = [
                        'id' => $data->id,
                        'location' => $data->patrolLocation->name,
                        'checkpoint' => $data->patrolCheckpoint->name,
                        'event' => $data->patrolEvent->name,
                        'note' => $data->note,
                        'latitude' => $data->latitude,
                        'longitude' => $data->longitude,
                        'created_at' => $data->created_at->diffForHumans(),
                        'created_by' => $data->patrolUser->name,
                        'views' => $data->views,
                        'comments' => $data->comments->count(),
                        'media' => [
                            'total' => $data->mediaItems->count(),
                            'image' => $media
                        ]
                    ];
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'welcome home',
                    'total' => $get->count(),
                    'data' => $show
                ], 200);
            }
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
