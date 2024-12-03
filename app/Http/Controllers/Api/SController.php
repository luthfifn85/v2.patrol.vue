<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sos;
use App\Models\SosComment;
use App\Models\SosMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class SController extends Controller
{
    public function index()
    {
        try
        {
            // role: admin & client
            if (Auth::user()->patrol_role_id == 1 || Auth::user()->patrol_role_id == 4)
            {
                $get = Sos::with('company:id,name', 'location:id,name', 'user:id,name')
                    ->where('company_id', Auth::user()->company_id)
                    ->whereDate('created_at', now())
                    ->latest()
                    ->get();

                $show = [];

                foreach ($get as $data)
                {
                    $show[] = [
                        'id' => $data->id,
                        'location' => $data->location->name,
                        'title' => $data->title,
                        'created_at' => $data->created_at->diffForHumans(),
                    ];
                }

                return response()->json([
                    'status' => 'success',
                    'message'=> 'sos data generated',
                    'total' => $get->count(),
                    'data' => $show
                ], 200);
            }

            // role: chief & guard
            else
            {
                $get = Sos::with('company:id,name', 'location:id,name', 'user:id,name')
                    ->where('patrol_location_id', Auth::user()->patrol_location_id)
                    ->whereDate('created_at', now())
                    ->latest()
                    ->get();

                $show = [];

                foreach ($get as $data)
                {
                    $show[] = [
                        'id' => $data->id,
                        'location' => $data->location->name,
                        'title' => $data->title,
                        'created_at' => $data->created_at->diffForHumans(),
                    ];
                }

                return response()->json([
                    'status' => 'success',
                    'message'=> 'sos data generated',
                    'total' => $get->count(),
                    'data' => $show
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

    public function store(Request $request)
    {
        DB::beginTransaction();

        try
        {
            $request->validate([
                'title' =>['required'],
                'note' => ['required'],
                'latitude' => ['required'],
                'longitude' => ['required'],
                'attachment' => ['required', 'mimes:pdf', 'max:1024'],
                'filename' => ['required', 'array'],
                'filename.*' => ['required', 'mimes:png,jpg,jpeg,webp,mp4']
            ]);

            $file = $request->file('filename');
            $files = $request->file('attachment');
            $path = '../../v2.images.patrol/sos';
            $paths = '../../v2.images.patrol/sos/attachment';

            $store = Sos::create([
                'company_id' => Auth::user()->company_id,
                'patrol_location_id' => Auth::user()->patrol_location_id,
                'patrol_user_id' => Auth::id(),
                'title' => $request->title,
                'note' => $request->note,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'attachment' => Auth::user()->company_id . '_' . Auth::user()->patrol_location_id . '_' . now()->format('Ymd_His') . '_' . uniqid() . '.' . $files->getClientOriginalExtension()
            ]);

            foreach ($file as $data)
            {
                $media = SosMedia::create([
                    'sos_id' => $store->id,
                    'filename' => Auth::user()->company_id . '_' . Auth::user()->patrol_location_id . '_' . now()->format('Ymd_His') . '_' . uniqid() . '.' . $data->getClientOriginalExtension()
                ]);

                $data->move($path, $media->filename);
            }

            $files->move($paths, $store->attachment);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'sos data created',
                'data' => [
                    'location' => $store->location->name,
                    'title' => $store->title,
                    'created_at' => now(),
                    'created_by' => $store->user->name
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

    public function show(Sos $id)
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
                    'created_by' => $data->user->name
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
                'message' => 'sos information generated',
                'data' => [
                    'id' => $id->id,
                    'location' => $id->location->name,
                    'title' => $id->title,
                    'note' => $id->note,
                    'latitude' => $id->latitude,
                    'longitude' => $id->longitude,
                    'created_at' => $id->created_at->format('d M y, H:i A'),
                    'created_by' => $id->user->name,
                    'attachment' => $id->attachment,
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

    public function comment(Request $request, Sos $id)
    {
        {
            DB::beginTransaction();

            try
            {
                $request->validate([
                    'message' => ['required']
                ]);

                $store = SosComment::create([
                    'sos_id' => $id->id,
                    'patrol_user_id' => Auth::id(),
                    'message' => $request->message
                ]);

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'sos comment created',
                    'data' => [
                        'message' => $store->message,
                        'created_at' => $store->created_at,
                        'created_by' => $store->user->name
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
    }
}
