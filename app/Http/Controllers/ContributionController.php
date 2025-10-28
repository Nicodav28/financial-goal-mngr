<?php

namespace App\Http\Controllers;

use App\Services\ContributionService;
use Illuminate\Http\Request;

class ContributionController extends Controller
{
    public function __construct(private readonly ContributionService $contributionService)
    {
        //
    }

    public function index()
    {
        return $this->contributionService->getAllContributions();
    }

    public function show($id)
    {
        return $this->contributionService->getContributionById($id);
    }

    public function store(Request $request)
    {
        return $this->contributionService->createContribution($request->all());
    }

    public function update(Request $request, $id)
    {
        return $this->contributionService->updateContribution($id, $request->all());
    }

    public function destroy($id)
    {
        return $this->contributionService->deleteContribution($id);
    }
}
