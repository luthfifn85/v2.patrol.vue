<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Models\PatrolUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        try
        {
            $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required', 'min:8']
            ]);

            $login = $request->only('email', 'password');

            if (Auth::guard('app')->attempt($login))
            {
                $user = PatrolUser::where('email', $request->email)
                    ->where('is_active', 1)
                    ->first();

                // active user
                if ($user)
                {
                    $token = $user->createToken('auth_token')->plainTextToken;

                    return response()->json([
                        'status' =>'success',
                        'message' => 'login',
                        'meta' => [
                            'token' => $token,
                            'type' => 'bearer'
                        ],
                        'data' => [
                            'id' => $user->id,
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

                // inactive user
                else
                {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'status: inactive'
                    ], 401);
                }
            }

            return response()->json([
                'status' => 'error',
                'message' => 'wrong email or password'
            ], 401);
        }

        catch (Throwable $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
