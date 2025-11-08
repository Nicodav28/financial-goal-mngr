<?php

namespace App\Providers;

use App\Repositories\Contracts\AttachmentRepositoryInterface;
use App\Repositories\Contracts\ContributionRepositoryInterface;
use App\Repositories\Contracts\GoalRepositoryInterface;
use App\Repositories\Contracts\GroupRepositoryInterface;
use App\Repositories\Contracts\InviteRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\AttachmentRepository;
use App\Repositories\Eloquent\ContributionRepository;
use App\Repositories\Eloquent\GoalRepository;
use App\Repositories\Eloquent\GroupRepository;
use App\Repositories\Eloquent\InviteRepository;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(GroupRepositoryInterface::class, GroupRepository::class);
        $this->app->bind(InviteRepositoryInterface::class, InviteRepository::class);
        $this->app->bind(GoalRepositoryInterface::class, GoalRepository::class);
        $this->app->bind(ContributionRepositoryInterface::class, ContributionRepository::class);
        $this->app->bind(AttachmentRepositoryInterface::class, AttachmentRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
