<?php

namespace Soda\Reports;

use Soda\Reports\Console\Seed;
use Soda\Reports\Models\Report;
use Soda\Reports\Console\Migrate;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class SodaReportsServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Soda\Reports\Controllers';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->loadViewsFrom(__DIR__.'/../views', 'soda-reports');
        $this->publishes([__DIR__.'/../config' => config_path('soda')], 'soda.reports.config');
        $this->loadMigrationsFrom(__DIR__.'/../migrations');

        Relation::morphMap([
            'Report'  => Report::class,
        ]);

        app('soda.menu')->menu('sidebar', function ($menu) {
            $menu->addItem('Reports', [
                'url'         => route('soda.reports.index'),
                'label'       => 'Reports',
                'icon'        => 'fa fa-bar-chart',
                'isCurrent'   => soda_request_is('reports*'),
                'permissions' => 'view-reports',
            ]);
        });
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/reports.php', 'soda.reports');

        $this->commands([
            Migrate::class,
            Seed::class,
        ]);
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        Route::group([
            'middleware' => 'web',
            'namespace'  => $this->namespace,
        ], function ($router) {
            require __DIR__.'/../routes/web.php';
        });
    }
}
