<?php

namespace App\Providers;

use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Psy\Util\Str;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        Passport::ignoreMigrations();
        $this->registerPolicies(); 
        Passport::routes();
        Client::creating(function (Client $client) {
            $client->incrementing = false;
            $client->id = \Illuminate\Support\Str::uuid();
        });
        Client::retrieved(function (Client $client) {
            $client->incrementing = false;
        });
    }
}
