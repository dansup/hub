<?php
  /* 
    *   Hub
    *   Search Controller 
    *
    */
namespace App\Controllers;

class Search {

    private $templates;

    public function __construct(\League\Plates\Engine $templates)
    {
        $this->templates = $templates;
    }

    public function getResults() {
        $app = \Slim\Slim::getInstance();
        $req = $app->request;
        $ip = $req->getIp();
        $types = ['node','service','people'];
        $query = (!empty($req->params('q'))) ? filter_var($req->params('q')) : false;
        $page = (intval($req->params('p'))) ? intval($req->params('p')) : 1;
        $type = (!empty($req->params('t')) && in_array($req->params('t'), $types)) ? filter_var($req->params('t')) : 'node';
        $show_results = ($query && strlen($query) > 2) ? true : false;
        return $this->templates->render('search', 
            [
            'search'=>null,
            'ip'=>$ip,
            'query'=>$query, 
            'type'=>$type, 
            'page'=>$page, 
            'show_results'=>$show_results, 
            'lang'=>'en-US',
            'timestamp'=>time()
            ]);
    }
}