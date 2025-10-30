<?php

namespace App\Repositories\Eloquent;

use App\Models\Contribution;
use App\Repositories\Contracts\ContributionRepositoryInterface;

class ContributionRepository implements ContributionRepositoryInterface
{
    public function findAll()
    {
        return Contribution::all();
    }

    public function findById($id)
    {
        return Contribution::findOrFail($id);
    }

    public function create(array $data)
    {
        return Contribution::create($data);
    }

    public function update($id, array $data)
    {
        $contribution = Contribution::findOrFail($id);
        $contribution->update($data);
        return $contribution;
    }

    public function delete($id)
    {
        return Contribution::destroy($id);
    }
}
