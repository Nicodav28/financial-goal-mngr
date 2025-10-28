<?php

namespace App\Services;

use App\Repositories\Contracts\GroupRepositoryInterface;

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
        return $this->groupRepository->create($data);
    }

    public function updateGroup($id, array $data)
    {
        return $this->groupRepository->update($id, $data);
    }

    public function deleteGroup($id)
    {
        return $this->groupRepository->delete($id);
    }
}
