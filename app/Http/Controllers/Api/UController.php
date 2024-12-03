<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Models\PatrolUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UController extends Controller
{
    public function index()
    {
        try
        {
            $user = PatrolUser::where('id', Auth::id())
                ->first();

            return response()->json([
                'status' => 'success',
                'message' => 'user data generated',
                'data' => [
                    'role_id' => $user->patrol_role_id,
                    'company_id' => $user->company_id,
                    'location_id' => $user->patrol_location_id ?? null,
                    'name' => $user->name,
                    'company' => $user->company->name,
                    'role' => $user->patrolRole->name,
                    'location' => $user->patrolLocation->name ?? 'All',
                    'email' => $user->email,
                    'mobile_no' => $user->mobile_no ?? null
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
                'message' => 'user profile updated',
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
                'message' => 'user password updated',
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
                'photo' => ['required', 'mimes:png,jpg,jpeg,webp']
            ]);

            $get = PatrolUser::where('id', Auth::id())
                ->first();

            $file = $request->file('photo');
            $path = '../../v2.patrol.vue-images/user';

            $get->update([
                'photo' => now()->format('Ymd_His') . '_' . uniqid() . '.' . $file->getClientOriginalExtension()
            ]);

            $file->move($path, $get->photo);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'user profile photo updated',
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
}
