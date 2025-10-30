<?php

namespace App\Repositories\Eloquent;

use App\Models\Goal;
use App\Repositories\Contracts\GoalRepositoryInterface;

class GoalRepository implements GoalRepositoryInterface
{
    public function findAll()
    {
        return Goal::all();
    }

    public function findById($id)
    {
        return Goal::findOrFail($id);
    }

    public function create(array $data)
    {
        return Goal::create($data);
    }

    public function update($id, array $data)
    {
        $goal = Goal::findOrFail($id);
        $goal->update($data);
        return $goal;
    }

    public function delete($id)
    {
        return Goal::destroy($id);
    }
}
