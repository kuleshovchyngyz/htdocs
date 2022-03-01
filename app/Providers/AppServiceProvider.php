<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\ApiInterface;
use App\Adapters\MegaIndexApiAdapter;
use App\Http\View\Composers\ProjectComposer;
use App\Project;
use Illuminate\Support\Facades\View;
use DB;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ApiInterface::class, function ($app) {
            switch (config('app.api', 'megaindex')) {
                case 'megaindex':
                    return new MegaIndexApiAdapter;
                default:
                    throw new \RuntimeException("Unknown API");
            }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(
            '*',
            ProjectComposer::class
        );

        // DB::listen(function ($query) {
        //     dump($query->sql);
        //     dump($query->bindings);
        //     // $query->time
        // });
    }
}
