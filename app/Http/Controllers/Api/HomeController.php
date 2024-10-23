<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patrol;
use App\Models\PatrolCheckpoint;
use App\Models\PatrolComment;
use App\Models\PatrolMedia;
use App\Models\PatrolUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class HomeController extends Controller
{
    public function index()
    {
        try
        {
            // inactive user
            if (Auth::user()->is_active ==  2)
            {
                Auth::user()->tokens()->delete();

                return response()->json([
                    'status' => 'success',
                    'message' => 'status: inactive'
                ], 401);
            }

            // active user
            // role: admin & client
            if (Auth::user()->patrol_role_id == 1 || Auth::user()->patrol_role_id == 4)
            {
                $get = Patrol::with('patrolLocation:id,name', 'patrolCheckpoint:id,name', 'patrolEvent:id,name', 'patrolUser:id,name')
                    ->where('company_id', Auth::user()->company_id)
                    ->whereDate('created_at', now())
                    ->latest()
                    ->get();

                $show = [];

                foreach ($get as $data)
                {
                    $media = [];

                    foreach ($data->media as $list)
                    {
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
                        'comments' => $data->comment->count(),
                        'media' => [
                            'total' => $data->media->count(),
                            'image' => $media
                        ]
                    ];
                }

                return response()->json([
                    'status' => 'success',
                    'message'=> 'welcome home',
                    'meta' => [
                        'total' => $get->count(),
                        'data' => $show
                    ]
                ], 200);
            }

            // role: chief & guard
            else
            {
                $get = Patrol::with('patrolLocation:id,name', 'patrolCheckpoint:id,name', 'patrolEvent:id,name', 'patrolUser:id,name')
                    ->where('patrol_location_id', Auth::user()->patrol_location_id)
                    ->whereDate('created_at', now())
                    ->latest()
                    ->get();

                $show = [];

                foreach ($get as $data)
                {
                    $media = [];

                    foreach ($data->media as $list)
                    {
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
                        'comments' => $data->comment->count(),
                        'media' => [
                            'total' => $data->media->count(),
                            'image' => $media
                        ]
                    ];
                }

                return response()->json([
                    'status' => 'success',
                    'message'=> 'welcome home',
                    'meta' => [
                        'total' => $get->count(),
                        'data' => $show
                    ]
                ], 200);
            }
        }

        catch (Throwable $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function edit(Request $request)
    {
        DB::beginTransaction();

        try
        {
            $request->validate([
                'name' => ['required'],
                'mobile_no' => ['required', 'numeric', 'digits_between:10,13']
            ]);

            $get = PatrolUser::where('id', Auth::id())
                ->first();

            $get->update([
                'name' => $request->name,
                'mobile_no' => $request->mobile_no
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'users profile updated',
                'data' => [
                    'name' => $get->name,
                    'mobile_no' => $get->mobile_no
                ]
            ], 200);
        }

        catch (Throwable $e)
        {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function password(Request $request)
    {
        DB::beginTransaction();

        try
        {
            $request->validate([
                'password' => ['required', 'min:8'],
            ]);

            $get = PatrolUser::where('id', Auth::id())
                ->first();

            $get->update([
                'password' => Hash::make($request->password)
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'users password updated',
                'data' => [
                    'password' => 'secret'
                ]
            ], 200);
        }

        catch (Throwable $e)
        {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function photo(Request $request)
    {
        DB::beginTransaction();

        try
        {
            $request->validate([
                'photo' => ['required', 'mimes:png,jpg,jpeg,webp', 'max:1024']
            ]);

            $get = PatrolUser::where('id', Auth::id())
                ->first();

            $file = $request->file('photo');
            $path = '../../v2.images.patrol/user';

            $get->update([
                'photo' => now()->format('Ymd_His') . '_' . uniqid() . '.' . $file->getClientOriginalExtension()
            ]);

            $file->move($path, $get->photo);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'users profile photo updated',
                'data' => [
                    'photo' => $get->photo
                ]
            ], 200);
        }

        catch (Throwable $e)
        {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function store(Request $request, PatrolCheckpoint $id)
    {
        DB::beginTransaction();

        try
        {
            $request->validate([
                'patrol_event_id' =>['required'],
                'note' => ['required'],
                'latitude' => ['required'],
                'longitude' => ['required'],
                'filename' => ['required', 'array'],
                'filename.*' => ['required', 'mimes:png,jpg,jpeg,webp', 'max:1024']
            ]);

            $file = $request->file('filename');
            $path = '../../v2.images.patrol';

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

            foreach ($file as $data)
            {
                $media = PatrolMedia::create([
                    'patrol_id' => $store->id,
                    'filename' => now()->format('Ymd_His') . '_' . uniqid() . '.' . $data->getClientOriginalExtension()
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
        }

        catch (Throwable $e)
        {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function patrol(Patrol $id)
    {
        $id->views++;

        try
        {
            $id->update([
                'views' => $id->views
            ]);

            $comment = [];

            $comments = $id->comment()->orderBy('created_at', 'desc')->get();

            foreach ($comments as $data)
            {
                $comment[] = [
                    'id' => $data->id,
                    'message' => $data->message,
                    'created_at' => $data->created_at->diffForHumans(),
                    'created_by' => $data->patrolUser->name
                ];
            }

            $media = [];

            foreach ($id->media as $data)
            {
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
                        'total' => $id->comment->count(),
                        'data' => $comment
                    ],
                    'media' => [
                        'total' => $id->media->count(),
                        'data' => $media
                    ]
                ]
            ], 200);
        }

        catch (Throwable $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function comment(Request $request, Patrol $id)
    {
        DB::beginTransaction();

        try
        {
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
        }

        catch (Throwable $e)
        {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function scan($id)
    {
        try
        {
            // role: admin & client
            if (Auth::user()->patrol_role_id == 1 || Auth::user()->patrol_role_id == 4)
            {
                $get = PatrolCheckpoint::where('uuid', $id)
                    ->where('company_id', Auth::user()->company_id)
                    ->first();
            }

            // role: chief & guard
            else
            {
                $get = PatrolCheckpoint::where('uuid', $id)
                    ->where('patrol_location_id', Auth::user()->patrol_location_id)
                    ->first();
            }
            

            if ($get)
            {
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
           
        }

        catch (Throwable $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'patrol qrcode: unknown'
            ], 400);
        }
    }
}
