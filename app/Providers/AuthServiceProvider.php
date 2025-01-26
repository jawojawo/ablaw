<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\AdminDeposit;
use App\Models\AdministrativeFee;
use App\Models\Associate;
use App\Models\Billing;
use App\Models\Client;
use App\Models\CustomEvent;
use App\Models\Deposit;
use App\Models\Hearing;
use App\Models\LawCase;
use App\Models\OfficeExpense;
use App\Models\User;
use App\Policies\AssociatePolicy;
use App\Policies\CaseBillingPolicy;
use App\Policies\CaseDepositPolicy;
use App\Policies\CaseExpensePolicy;
use App\Policies\CaseHearingPolicy;
use App\Policies\ClientPolicy;
use App\Policies\CustomEventPolicy;
use App\Policies\LawCasePolicy;
use App\Policies\OfficeExpensePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        LawCase::class => LawCasePolicy::class,
        AdminDeposit::class => CaseDepositPolicy::class,
        AdministrativeFee::class => CaseExpensePolicy::class,
        Hearing::class => CaseHearingPolicy::class,
        Billing::class => CaseBillingPolicy::class,
        Deposit::class => CaseDepositPolicy::class,
        Client::class => ClientPolicy::class,
        Associate::class => AssociatePolicy::class,
        OfficeExpense::class => OfficeExpensePolicy::class,
        CustomEvent::class => CustomEventPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
