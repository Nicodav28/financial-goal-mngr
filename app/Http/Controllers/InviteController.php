<?php

namespace App\Http\Controllers;

use App\Services\GroupService;
use App\Services\InviteService;
use Illuminate\Http\Request;

class InviteController extends Controller
{
    public function __construct(private readonly InviteService $inviteService, private readonly GroupService $groupService)
    {
        //
    }

    public function index()
    {
        return $this->inviteService->getAllInvites();
    }

    public function show($id)
    {
        return $this->inviteService->getInviteById($id);
    }

    public function store(Request $request)
    {
        return $this->inviteService->createInvite($request->all());
    }

    public function update(Request $request, $id)
    {
        return $this->inviteService->updateInvite($id, $request->all());
    }

    public function destroy($id)
    {
        return $this->inviteService->deleteInvite($id);
    }

    public function acceptInvite($inviteCode)
    {
        try {
            $invite = $this->inviteService->acceptInvite($inviteCode);
            $joinedGroup = $this->groupService->joinGroupUsingInvite($invite);

            return response()->json($joinedGroup, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to accept invite: ' . $e->getMessage()], 500);
        }
    }
}
