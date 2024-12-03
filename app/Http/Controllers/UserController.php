<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereNot('id', Auth::user()->id)
            ->with('role:id,name')
            ->get();

        $roles = Role::where('is_active', 1)
            ->get();

        return Inertia::render('User/Index', [
            'title' => 'Users',
            'users' => $users->toArray(),
            'userCount' => $users->count(),
            'roles' => $roles->toArray()
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'role_id' => ['required', 'exists:roles,id,is_active,1'],
                'email' => ['required', 'unique:users,email', 'email', 'max:255', 'string'],
                'password' => ['required', 'min:8']
            ]);

            User::create([
                ...$validated,
                'password' => Hash::make($validated['password'])
            ]);

            return redirect()->back()->with('success', [
                'message' => 'User successfully created',
                'id' => uniqid()
            ]);
        } catch (Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('error', [
                'message' => 'Failed to create user. Something went wrong',
                'id' => uniqid()
            ]);
        }
    }

    public function update(Request $request, User $get)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'name' => ['required']
            ]);

            $get->update([
                'name' => $request->name
            ]);

            DB::commit();

            return back()->with('success', 'Name updated');
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function changePassword(Request $request, User $userBind)
    {
        try {
            $validated = $request->validate([
                'password' => ['required', 'min:8']
            ]);

            $userBind->update([
                'password' => Hash::make($validated['password'])
            ]);

            return redirect()->back()->with('success', [
                'message' => 'Password successfully updated',
                'id' => uniqid()
            ]);
        } catch (Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('success', [
                'message' => 'Failed to update password. Something went wrong',
                'id' => uniqid()
            ]);
        }
    }
}
