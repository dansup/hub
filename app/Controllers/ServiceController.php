<?php
  /* 
    *   Hub
    *   Service Controller 
    *
    */
namespace App\Controllers;

class ServiceController {

    private $templates;
    private $app;

    public function __construct(\League\Plates\Engine $templates)
    {
        $this->templates = $templates;
        $this->app = \Slim\Slim::getInstance();
    }

    public function getAllServices() {
        $service = new \App\Models\Service;
        $req = $this->app->request;
        $page = (!empty($req->params('page')) && intval($req->params('page'))) ? intval($req->params('page')) : 1;
        $ob = (!empty($req->params('ob')) && intval($req->params('ob'))) ? intval($req->params('ob')) : 1;
        return $this->templates->render(
            'service::browse', [
            'service'=>$service,
            'ip'=>$req->getIp(),
            'page'=>$page, 
            'order_by'=>$ob
            ]);
        }

    public function getService($id) {
        $service = new \App\Models\Service;
        $data = $service->getService($id);
        return $this->templates->render('service::view', ['service'=>$data]);
    }

}

