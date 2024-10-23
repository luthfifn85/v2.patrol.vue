<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PatrolUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

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
                            'role' => $user->patrolRole->name,
                            'name' => $user->name,
                            'email' => $user->email,
                            'mobile_no' => $user->mobile_no ?? null,
                            'company' => $user->company->name,
                            'location' => $user->patrolLocation->name ?? 'All'
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
