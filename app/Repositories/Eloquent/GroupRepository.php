<?php

namespace App\Repositories\Eloquent;

use App\Models\Group;
use App\Repositories\Contracts\GroupRepositoryInterface;

class GroupRepository implements GroupRepositoryInterface
{
    public function findAll()
    {
        return Group::with('users')->get();
    }

    public function findById($id)
    {
        return Group::findOrFail($id);
    }

    public function create(array $data)
    {
        return Group::create($data);
    }

    public function update($id, array $data)
    {
        $group = Group::findOrFail($id);
        $group->update($data);
        return $group;
    }

    public function delete($id)
    {
        return Group::destroy($id);
    }
}
