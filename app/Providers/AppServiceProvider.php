<?php

namespace App\Providers;

use function GuzzleHttp\Psr7\str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use Laravel\Passport\PersonalAccessClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Client::creating(function (Client $client) {
            $client->incrementing = false;
            $client->id = Str::uuid()->toString();
        });
        PersonalAccessClient::creating(function (PersonalAccessClient $personalAccessClient) {
            $personalAccessClient->incrementing = false;
            $personalAccessClient->id = Str::uuid()->toString();
        });
        Client::retrieved(function (Client $client) {
            $client->incrementing = false;
        });
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Passport::ignoreMigrations();
    }
}
