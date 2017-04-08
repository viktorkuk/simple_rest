<?php

namespace App;

use Silex\Application;

class RoutesLoader
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->instantiateControllers();

    }

    private function instantiateControllers()
    {
        $this->app['books.controller'] = function() {
            return new Controllers\BooksController($this->app['books.service']);
        };
        
        $this->app['auth.controller'] = function() {
            return new Controllers\AuthController($this->app['auth.service']);
        };
    }

    public function bindRoutesToControllers()
    {
        $api = $this->app["controllers_factory"];

        $api->get('/books/{page}/', "books.controller:getAll");
        $api->get('/book/{id}/', "books.controller:getOne");
        $api->get('/bookrating/{id}/', "books.controller:getRating");
        
        $api->post('/book/', "books.controller:save");//->before($this->app['auth.service']->checkAuth());
        $api->put('/book/{id}/', "books.controller:update");//->before($this->app['auth.service']->checkAuth());
        $api->delete('/book/{id}/', "books.controller:delete");//->before($this->app['auth.service']->checkAuth());      
        $api->options('/book/', "books.controller:options");
        
        $api->get('/authenticate/', "auth.controller:isLogin");
        $api->post('/authenticate/', "auth.controller:login");
        $api->delete('/authenticate/', "auth.controller:logout");
        $api->options('/authenticate/', "auth.controller:options");

        $this->app->mount($this->app["api.endpoint"].'/'.$this->app["api.version"], $api);
    }
}

