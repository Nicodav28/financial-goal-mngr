<?php

namespace App\Services;

use App\Repositories\Contracts\ContributionRepositoryInterface;

class ContributionService
{
    public function __construct(private readonly ContributionRepositoryInterface $contributionRepository)
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
