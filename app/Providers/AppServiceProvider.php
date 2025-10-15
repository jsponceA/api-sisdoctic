<?php

namespace App\Providers;

use App\Models\User;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Broadcast::routes([
            "prefix" => "api",
            "middleware" => ["auth:sanctum"]
        ]);

        Scramble::registerApi('v1/dashboard', [
            'info' =>
                ['version' => '1.0','description' => 'End points para los mantenimientos del dashboard']])
            ->routes(function (Route $route) {
                return Str::startsWith($route->uri, 'api/v1/dashboard');
            })
            ->expose(
                ui: '/docs/api/v1/dashboard',
                document: '/docs/v1/dashboard/openapi.json',
            )
            ->afterOpenApiGenerated(function (OpenApi $openApi) {
               $openApi->secure(SecurityScheme::http('bearer'));
            });


        /*Gate::define('viewApiDocs', function (User $user) {
            return in_array($user->correo, ['jponce@gmail.com']);
        });*/


        Scramble::configure()
            ->expose(false);

    }


}
