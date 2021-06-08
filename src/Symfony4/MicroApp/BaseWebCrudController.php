<?php

namespace ZnLib\Web\Symfony4\MicroApp;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ZnCore\Domain\Interfaces\Service\CrudServiceInterface;
use ZnCore\Domain\Libs\Query;

abstract class BaseWebCrudController extends BaseWebController
{

    protected $service;

    protected function setService(CrudServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): Response
    {
        $query = new Query();
        $query->perPage($request->query->get('per-page'));
        $query->page($request->query->get('page'));
        $dataProvider = $this->service->getDataProvider($query);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
