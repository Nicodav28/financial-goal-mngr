<?php

namespace App\Http\Controllers;

use App\Services\GroupService;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function __construct(private readonly GroupService $groupService)
    {
        //
    }

    public function index()
    {
        return $this->groupService->getAllGroups();
    }

    public function show($id)
    {
        return $this->groupService->getGroupById($id);
    }

    public function store(Request $request)
    {
        return $this->groupService->createGroup($request->all());
    }

    public function update(Request $request, $id)
    {
        return $this->groupService->updateGroup($id, $request->all());
    }

    public function destroy($id)
    {
        return $this->groupService->deleteGroup($id);
    }

    // public function leaveGroup($id)
    // {
    //     return $this->groupService->leaveGroup($id);
    // }
}
