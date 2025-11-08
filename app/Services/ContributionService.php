<?php

namespace App\Services;

use App\Repositories\Contracts\ContributionRepositoryInterface;
use App\Repositories\Contracts\GoalRepositoryInterface;

class ContributionService
{
    public function __construct(private readonly ContributionRepositoryInterface $contributionRepository, private readonly GoalRepositoryInterface $goalRepository)
    {
        //
    }

    public function getAllContributions()
    {
        return $this->contributionRepository->findAll();
    }

    public function getContributionById($id)
    {
        return $this->contributionRepository->findById($id);
    }

    public function createContribution(array $data)
    {
        $goal = $this->goalRepository->findById($data['goal_id']);

        if (!$goal) {
            throw new \Exception('Goal not found');
        }
        // dd($goal->participants()->get()->toArray());

        return $this->contributionRepository->create($data);
    }

    public function updateContribution($id, array $data)
    {
        return $this->contributionRepository->update($id, $data);
    }

    public function deleteContribution($id)
    {
        return $this->contributionRepository->delete($id);
    }
}
