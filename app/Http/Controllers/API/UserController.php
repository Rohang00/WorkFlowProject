<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\UserStoreRequest;
use App\Http\Requests\Project\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function store(UserStoreRequest $request)
    {
        $user = User::create($request->validated());
        $validated['password'] = Hash::make($user['password']);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        return response()->json([
            'message' => 'User created successfully.',
            'user' => $user,
        ], 201);
    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    public function edit(User $user)
    {
        return response()->json($user);
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        $user->update($request->validated());
        return response()->json(['message' => 'User updated successfully!', 'user' => $user]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted successfully!']);
    }
}
