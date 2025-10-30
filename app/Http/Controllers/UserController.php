<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
        //
    }

    public function index()
    {
        try {
            $users = $this->userService->getAllUsers();
            return response()->json($users, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve users'], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $user = $this->userService->getUserById($id);
            return response()->json($user, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve user'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $user = $this->userService->createUser($request->all());
            return response()->json($user, 201);
        } catch (\Exception $e) {
            dd($e);

            return response()->json(['error' => 'Failed to create user'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = $this->userService->updateUser($id, $request->all());
            return response()->json($user, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update user'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->userService->deleteUser($id);
            return response()->json(['message' => 'User deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete user'], 500);
        }
    }
}
