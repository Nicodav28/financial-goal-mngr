<?php

namespace App\Http\Controllers;

use App\Services\GroupService;
use App\Shared\ResponseHandler;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function __construct(private readonly GroupService $groupService)
    {
        //
    }

    public function index()
    {
        try {
            $groups = $this->groupService->getAllGroups();
            return ResponseHandler::response(200, 'Group:index', null, null, $groups);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Group:index', 'Failed to retrieve groups', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $group = $this->groupService->getGroupById($id);
            if (!$group) {
                return ResponseHandler::response(404, 'Group:show', 'Group not found', null);
            }
            return ResponseHandler::response(200, 'Group:show', null, null, $group);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Group:show', 'Failed to retrieve group', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $group = $this->groupService->createGroup($request->all());
            return ResponseHandler::response(201, 'Group:store', null, null, $group);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Group:store', 'Failed to create group', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $group = $this->groupService->updateGroup($id, $request->all());
            return ResponseHandler::response(200, 'Group:update', null, null, $group);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Group:update', 'Failed to update group', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->groupService->deleteGroup($id);
            return ResponseHandler::response(200, 'Group:destroy', 'Group deleted successfully', null);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Group:destroy', 'Failed to delete group', $e->getMessage());
        }
    }

    // public function leaveGroup($id)
    // {
    //     return $this->groupService->leaveGroup($id);
    // }
}
