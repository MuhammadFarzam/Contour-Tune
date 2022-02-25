<?php

namespace App\Providers;

use App\Interfaces\IJsonFetchRecord;
use App\Interfaces\IUserDataRepository;
use App\Repositories\JsonDataRepository;
use App\Repositories\UserLogsDataRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(IJsonFetchRecord::class, JsonDataRepository::class);

        $this->app->bind(IUserDataRepository::class, UserLogsDataRepository::class);
    }
}
