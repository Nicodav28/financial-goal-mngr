<?php

namespace App\Repositories\Eloquent;

use App\Models\Goal;
use App\Repositories\Contracts\GoalRepositoryInterface;

class GoalRepository implements GoalRepositoryInterface
{
    public function findAll(string $userId)
    {
        return Goal::withSum('contributions', 'amount')
            ->where('owner_id', $userId)
            ->get()
            ->map(function ($goal) {
                $goal->contributions_sum_amount = $goal->contributions_sum_amount ?? 0;
                return $goal;
            });
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
