<?php
namespace App\Services;

class AuthService
{
    
    public $app;
    
    public function __construct($app)
    {
        $this->app = $app;
    }
    
    public function checkAuth()
    {
        if (!$this->isLogin()) {
            $this->app->abort(401);
        }
        return true;
    }
    
    public function isLogin()
    {
        if ($this->app['session']->has('is_admin')) {
            return $this->app['session']->get('is_admin') == $this->app['auth.token'];
        }
        return false;
    }
    
    public function login($secureString)
    {
        if ($secureString == $this->app['auth.token']) {
            $this->app['session']->set('is_admin',$secureString);
            return true;
        }
        return false;
    }
    
    public function logout()
    {
        $this->app['session']->remove('is_admin');
        return true;
    }
}
