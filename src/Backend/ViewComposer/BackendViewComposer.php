<?php

namespace Bambamboole\LaravelCms\Backend\ViewComposer;

use Bambamboole\LaravelCms\Backend\Routing\RouteGenerator;
use Illuminate\Routing\Router;
use Illuminate\View\View;

class BackendViewComposer
{
    protected Router $router;

    protected RouteGenerator $generator;

    public function __construct(Router $router, RouteGenerator $generator)
    {
        $this->router = $router;
        $this->generator = $generator;
    }

    public function compose(View $view)
    {
        $view->with('json', [
            'user' => auth()->user(),
            'routes' => $this->generator->generate(),
        ]);
    }
}
