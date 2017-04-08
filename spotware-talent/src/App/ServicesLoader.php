<?php

namespace App;

use Silex\Application;

class ServicesLoader
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function bindServicesIntoContainer()
    {
        $this->app['books.service'] = function() {
            return new Services\BooksService($this->app);
        };
        
        $this->app['auth.service'] = function() {
            return new Services\AuthService($this->app);
        };
    }
}

