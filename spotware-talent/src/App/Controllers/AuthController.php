<?php

namespace App\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController {
    
    protected $service;

    public function __construct($service)
    {
        $this->service = $service;
    }
    
    public function isLogin()
    {
        return new JsonResponse($this->service->isLogin());

    }
    
    public function login(Request $request)
    {
        if ($this->service->login($_POST['hashsum'])) {
            return new JsonResponse(array('status' => 'ok'));
        } else {
            return new JsonResponse(array('status' => 'fail', 'error' => 'Authorization failed'));
        }
    }
    
    public function logout()
    {        
        $this->service->logout();
        return new JsonResponse('ok');        
    }
    
    public function options()
    {                
        return new JsonResponse('ok');
    }
}
