<?php
namespace Soda\Reports;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Soda\Reports\Console\Migrate;
use Soda\Reports\Console\Seed;
use Soda\Reports\Models\Report;

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
        $this->loadMigrationsFrom(__DIR__.'/../migrations');

        Relation::morphMap([
            'Report'  => Report::class,
        ]);

        \SodaMenu::menu('sidebar', function ($menu) {
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
            require(__DIR__.'/../routes/web.php');
        });
    }
}
