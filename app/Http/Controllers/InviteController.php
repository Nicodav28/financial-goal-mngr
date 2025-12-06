<?php

namespace App\Http\Controllers;

use App\Services\GroupService;
use App\Services\InviteService;
use App\Shared\ResponseHandler;
use Illuminate\Http\Request;

class InviteController extends Controller
{
    public function __construct(private readonly InviteService $inviteService, private readonly GroupService $groupService)
    {
        //
    }

    public function index()
    {
        try {
            $invites = $this->inviteService->getAllInvites();
            return ResponseHandler::response(200, 'Invite:index', null, null, $invites);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Invite:index', 'Failed to retrieve invites', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $invite = $this->inviteService->getInviteById($id);
            if (!$invite) {
                return ResponseHandler::response(404, 'Invite:show', 'Invite not found', null);
            }
            return ResponseHandler::response(200, 'Invite:show', null, null, $invite);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Invite:show', 'Failed to retrieve invite', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $invite = $this->inviteService->createInvite($request->all());
            return ResponseHandler::response(201, 'Invite:store', null, null, $invite);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Invite:store', 'Failed to create invite', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $invite = $this->inviteService->updateInvite($id, $request->all());
            return ResponseHandler::response(200, 'Invite:update', null, null, $invite);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Invite:update', 'Failed to update invite', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->inviteService->deleteInvite($id);
            return ResponseHandler::response(200, 'Invite:destroy', 'Invite deleted successfully', null);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Invite:destroy', 'Failed to delete invite', $e->getMessage());
        }
    }

    public function acceptInvite($inviteCode)
    {
        try {
            $invite = $this->inviteService->acceptInvite($inviteCode);
            $joinedGroup = $this->groupService->joinGroupUsingInvite($invite);

            return ResponseHandler::response(200, 'Invite:accept', null, null, $joinedGroup);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Invite:accept', 'Failed to accept invite', $e->getMessage());
        }
    }
}
