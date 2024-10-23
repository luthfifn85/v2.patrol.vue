<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class LogoutController extends Controller
{
    public function index()
    {
        try
        {
            Auth::user()->tokens()->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'logout'
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
}
