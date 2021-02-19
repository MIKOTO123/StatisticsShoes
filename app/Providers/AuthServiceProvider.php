<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\AdminCategory;
use App\Models\Bookkeeping;
use App\Models\Gift;
use App\Models\Invoice;
use App\Models\Order;
use App\Policies\AdminCategoryPolicy;
use App\Policies\AdminPolicy;
use App\Policies\BookKeepPolicy;
use App\Policies\GiftPolicy;
use App\Policies\InvoicePolicy;
use App\Policies\OrderPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Tymon\JWTAuth\JWTGuard;
use Illuminate\Contracts\Auth\UserProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',

    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
