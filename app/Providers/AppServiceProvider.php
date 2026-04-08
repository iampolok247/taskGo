<?php

namespace App\Providers;

use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Policies\DepositPolicy;
use App\Policies\WithdrawalPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register policies
        Gate::policy(Deposit::class, DepositPolicy::class);
        Gate::policy(Withdrawal::class, WithdrawalPolicy::class);
    }
}
