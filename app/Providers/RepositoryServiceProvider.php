<?php

namespace App\Providers;

use App\Repositories\Interfaces\PDRepositoryInterface;
use App\Repositories\PDRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(PDRepositoryInterface::class, PDRepository::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
