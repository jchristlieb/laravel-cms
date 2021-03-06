<?php

namespace Bambamboole\LaravelCms\Api\Routing;

use Bambamboole\LaravelCms\Api\Http\Controllers\IssueTokenController;
use Bambamboole\LaravelCms\Api\Http\Controllers\MenuOrderController;
use Bambamboole\LaravelCms\Api\Http\Controllers\OpenApiController;
use Bambamboole\LaravelCms\Api\Http\Controllers\ResourceController;
use Bambamboole\LaravelCms\Api\Http\Controllers\SwaggerUiController;
use Bambamboole\LaravelCms\Core\Http\Middleware\Authenticate;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Router;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

class ApiRouter
{
    protected Router $router;

    protected string $prefix = 'api/cms/';

    protected array $middleware = [
        'throttle:60,1',
        SubstituteBindings::class,
    ];

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function registerApiRoutes()
    {
        $this->router
            ->middleware($this->middleware)
            ->as('cms.api.')
            ->prefix($this->prefix)
            ->group(function (Router $router) {
                $router->post('/auth/token', IssueTokenController::class)->name('auth.token');
                $router->get('/swagger-ui', SwaggerUiController::class)->name('swagger-ui');
                $router->get('/open-api/reference/definition.yaml', [OpenApiController::class, 'reference'])->name('oas.reference');
                $router->get('/open-api/{folder}/{file}', [OpenApiController::class, 'file'])->name('oas.file');
            });

        $this->registerProtectedApiRoutes();
    }

    protected function registerProtectedApiRoutes()
    {
        $this->router
            ->middleware([EnsureFrontendRequestsAreStateful::class, ...$this->middleware, Authenticate::class])
            ->as('cms.api.')
            ->prefix($this->prefix)
            ->group(function (Router $router) {
                $router->post('/menus/{name}/save_order', [MenuOrderController::class, 'update'])->name('menus.save_order');

                $router->get('/{resource}', [ResourceController::class, 'index'])->name('resources.index');
                $router->post('/{resource}', [ResourceController::class, 'store'])->name('resources.store');
                $router->get('/{resource}/{id}', [ResourceController::class, 'show'])->name('resources.show');
                $router->patch('/{resource}/{id}', [ResourceController::class, 'update'])->name('resources.update');
                $router->delete('/{resource}/{id}', [ResourceController::class, 'delete'])->name('resources.delete');
            });
    }
}
