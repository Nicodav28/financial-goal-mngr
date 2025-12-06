<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Shared\ResponseHandler;
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
            return ResponseHandler::response(200, 'User:index', null, null, $users);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'User:index', 'Failed to retrieve users', $e->getMessage());
        }
    }

    public function show(string $id)
    {
        try {
            $user = $this->userService->getUserById($id);
            if (!$user) {
                return ResponseHandler::response(404, 'User:show', 'User not found', null);
            }
            return ResponseHandler::response(200, 'User:show', null, null, $user);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'User:show', 'Failed to retrieve user', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $user = $this->userService->createUser($request->all());
            return ResponseHandler::response(201, 'User:store', null, null, $user);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'User:store', 'Failed to create user', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = $this->userService->updateUser($id, $request->all());
            return ResponseHandler::response(200, 'User:update', null, null, $user);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'User:update', 'Failed to update user', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->userService->deleteUser($id);
            return ResponseHandler::response(200, 'User:destroy', 'User deleted successfully', null);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'User:destroy', 'Failed to delete user', $e->getMessage());
        }
    }
}
