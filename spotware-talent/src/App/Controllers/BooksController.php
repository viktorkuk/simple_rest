<?php

namespace App\Controllers;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class BooksController
{

    protected $service;

    public function __construct($service)
    {
        $this->service = $service;
    }

    public function getOne($id)
    {
        return new JsonResponse($this->service->getOne($id));
    }

    public function getAll($page)
    {
        return new JsonResponse(array(
            'books' => $this->service->getPage($page),
            'pages' => ceil($this->service->rowCount() / (\App\Services\BooksService::PAGE_LIMIT))
        ));
    }

    public function save(Request $request)
    {
        $book = $this->getDataFromRequest($request);
        return new JsonResponse(array("id" => $this->service->save($book)));
    }

    public function update($id, Request $request)
    {
        $book = $this->getDataFromRequest($request);
        return new JsonResponse($this->service->update($id, $book));
    }

    public function delete($id)
    {
        return new JsonResponse($this->service->delete($id));
    }
    
    public function options()
    {
        return new JsonResponse('ok');
    }
    
    public function getRating($id)
    {
         return new JsonResponse($this->service->bookRating($id));
    }

    public function getDataFromRequest(Request $request)
    {
        parse_str($request->request->get("book"),$data);
        return $data;
    }
}
