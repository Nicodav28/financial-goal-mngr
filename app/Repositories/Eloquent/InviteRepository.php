<?php

namespace App\Repositories\Eloquent;

use App\Models\Invite;
use App\Repositories\Contracts\InviteRepositoryInterface;

class InviteRepository implements InviteRepositoryInterface
{
    public function findAll()
    {
        return Invite::all();
    }

    public function findById($id)
    {
        return Invite::findOrFail($id);
    }

    public function create(array $data)
    {
        $finalData = array_merge($data, [
            'invite_status' => '1',
            'invite_code' => uniqid(),
        ]);

        return Invite::create($finalData);
    }

    public function update($id, array $data)
    {
        $invite = Invite::findOrFail($id);
        $invite->update($data);
        return $invite;
    }

    public function delete($id)
    {
        return Invite::destroy($id);
    }

    public function findByInviteCode($inviteCode)
    {
        return Invite::where('invite_code', $inviteCode)->firstOrFail();
    }
}
