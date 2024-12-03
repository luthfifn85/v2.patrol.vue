<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Models\Patrol;
use App\Models\PatrolMedia;
use Illuminate\Http\Request;
use App\Models\PatrolComment;
use App\Models\PatrolCheckpoint;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PController extends Controller
{
    public function scan($uuid)
    {
        try {
            // role: admin & client
            if (Auth::user()->patrol_role_id == 1 || Auth::user()->patrol_role_id == 4) {
                $get = PatrolCheckpoint::where('uuid', $uuid)
                    ->where('company_id', Auth::user()->company_id)
                    ->first();
            }

            // role: chief & guard
            else {
                $get = PatrolCheckpoint::where('uuid', $uuid)
                    ->where('patrol_location_id', Auth::user()->patrol_location_id)
                    ->first();
            }


            if ($get) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'patrol qrcode: verified',
                    'data' => [
                        'id' => $get->id,
                        'uuid' => $get->uuid,
                        'name' => $get->name,
                        'location' => $get->patrolLocation->name,
                        'company' => $get->patrolLocation->company->name
                    ]
                ], 200);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'patrol qrcode: unknown'
            ], 404);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'patrol qrcode: unknown'
            ], 400);
        }
    }

    public function store(Request $request, PatrolCheckpoint $id)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'patrol_event_id' => ['required'],
                'note' => ['required'],
                'latitude' => ['required'],
                'longitude' => ['required'],
                'filename' => ['required', 'array'],
                'filename.*' => ['required', 'mimes:png,jpg,jpeg,webp,mp4']
            ]);

            $file = $request->file('filename');
            $path = '../../v2.patrol.vue-images/patrol';

            $store = Patrol::create([
                'company_id' => $id->patrolLocation->company->id,
                'patrol_location_id' => $id->patrolLocation->id,
                'patrol_checkpoint_id' => $id->id,
                'patrol_event_id' => $request->patrol_event_id,
                'patrol_user_id' => Auth::id(),
                'note' => $request->note,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude
            ]);

            foreach ($file as $data) {
                $media = PatrolMedia::create([
                    'patrol_id' => $store->id,
                    'filename' => Auth::user()->company_id . '_' . Auth::user()->patrol_location_id . '_' . now()->format('Ymd_His') . '_' . uniqid() . '.' . $data->getClientOriginalExtension()
                ]);

                $data->move($path, $media->filename);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'patrol data created',
                'data' => [
                    'location' => $store->patrolLocation->name,
                    'checkpoint' => $store->patrolCheckpoint->name,
                    'event' => $store->patrolEvent->name,
                    'created_at' => now(),
                    'created_by' => $store->patrolUser->name
                ]
            ], 201);
        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function show(Patrol $id)
    {
        $id->views++;

        try {
            $id->update([
                'views' => $id->views
            ]);

            $comment = [];

            $comments = $id->comments()->orderBy('created_at', 'desc')->get();

            foreach ($comments as $data) {
                $comment[] = [
                    'id' => $data->id,
                    'message' => $data->message,
                    'created_at' => $data->created_at->diffForHumans(),
                    'created_by' => $data->patrolUser->name
                ];
            }

            $media = [];

            foreach ($id->mediaItems as $data) {
                $media[] = [
                    'id' => $data->id,
                    'name' => $data->filename
                ];
            }

            return response()->json([
                'status' => 'success',
                'message' => 'patrol information generated',
                'data' => [
                    'id' => $id->id,
                    'location' => $id->patrolLocation->name,
                    'checkpoint' => $id->patrolCheckpoint->name,
                    'event' => $id->patrolEvent->name,
                    'note' => $id->note,
                    'latitude' => $id->latitude,
                    'longitude' => $id->longitude,
                    'created_at' => $id->created_at->format('d M y, H:i A'),
                    'created_by' => $id->patrolUser->name,
                    'views' => $id->views,
                    'comment' => [
                        'total' => $id->comments->count(),
                        'data' => $comment
                    ],
                    'media' => [
                        'total' => $id->mediaItems->count(),
                        'data' => $media
                    ]
                ]
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function comment(Request $request, Patrol $id)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'message' => ['required']
            ]);

            $store = PatrolComment::create([
                'patrol_id' => $id->id,
                'patrol_user_id' => Auth::id(),
                'message' => $request->message
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'patrol comment created',
                'data' => [
                    'message' => $store->message,
                    'created_at' => $store->created_at,
                    'created_by' => $store->patrolUser->name
                ]
            ], 201);
        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
