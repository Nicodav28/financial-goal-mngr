<?php

namespace App\Services;

use App\Models\Invite;
use App\Repositories\Contracts\GroupRepositoryInterface;
use Auth;

class GroupService
{
    public function __construct(private readonly GroupRepositoryInterface $groupRepository)
    {
        //
    }

    public function getAllGroups()
    {
        return $this->groupRepository->findAll();
    }

    public function getGroupById($id)
    {
        return $this->groupRepository->findById($id);
    }

    public function createGroup(array $data)
    {
        $data['created_by'] = Auth::user()->id;
        $group = $this->groupRepository->create($data);
        $group->users()->attach($data['created_by']);

        return $group->load('users');
    }

    public function updateGroup($id, array $data)
    {
        return $this->groupRepository->update($id, $data);
    }

    public function deleteGroup($id)
    {
        return $this->groupRepository->delete($id);
    }

    public function joinGroupUsingInvite(Invite $invite)
    {
        $group = $invite->group;
        $userId = $invite->invitee_id;

        if ($group->users()->where('user_id', $userId)->exists()) {
            throw new \Exception("User is already a member of the group.");
        }

        $group->users()->attach($userId);

        return $group->load('users');
    }
}
