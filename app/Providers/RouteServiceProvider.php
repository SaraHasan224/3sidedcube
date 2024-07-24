<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const DASHBOARD_HOME = '/dashboard';

    protected $namespace = 'App\Http\Controllers';

    protected $apiVersions = [
        ['version' =>  'v1', 'namespace' => 'App\Http\Controllers\Api\v1'],
    ];

    protected function mapWebRoutes()
    {
            Route::middleware('web')
                ->domain(env('WEB_DOMAIN'))
                ->namespace('App\Http\Controllers\Web')
                ->group(base_path('routes/web.php'));
    }
    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        foreach ($this->apiVersions as $apiVersion) {
            Route::prefix($apiVersion['version'])
                ->domain(env('API_DOMAIN'))
                ->middleware('api')
                ->namespace($apiVersion['namespace'])
                ->group(base_path('routes/' . $apiVersion['version'] . '/api.php'));

        }
    }



    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
//        RateLimiter::for('api', function (Request $request) {
//            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
//        });

        $this->configureRateLimiting();

        $this->mapWebRoutes();
        $this->mapApiRoutes();

//        $this->routes(function () {
//            Route::middleware('api')
//                ->prefix('api')
//                ->group(base_path('routes/api.php'));
//
//            Route::middleware('web')
//                ->group(base_path('routes/web.php'));
//        });
    }
}
