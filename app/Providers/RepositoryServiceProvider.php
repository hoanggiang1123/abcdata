<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $toBinds = [
            \App\Interfaces\BaseRepositoryInterface::class => \App\Repositories\BaseRepository::class,
            \App\Interfaces\LinkRepositoryInterface::class => \App\Repositories\LinkRepository::class,
        ];

        foreach($toBinds as $key => $value) {
            $this->app->bind($key, $value);
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
