<?php

namespace App\Http\Controllers;

use App\Services\InviteService;
use Illuminate\Http\Request;

class InviteController extends Controller
{
    public function __construct(private readonly InviteService $inviteService)
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
}
