<?php
  /* 
    *   Hub
    *   People Controller 
    *
    */
namespace App\Controllers;

use App\Models\People;

class PeopleController {

    private $templates;
    private $app;

    public function __construct(\League\Plates\Engine $templates)
    {
        $this->templates = $templates;
        $this->app = \Slim\Slim::getInstance();
        $this->req = $this->app->request;
    }

    public function getDirectory() {
        $order_by = $this->app->request->params('ob');
        $page = $this->app->request->params('page');
        $order_by_options = ['options' => ['default' => 1,'min_range' => 1,'max_range' => 6] ];
        $order_by = isset($order_by) ? filter_var($order_by, FILTER_VALIDATE_INT, $order_by_options) : 1;
        $page_options = ['options' => ['default' => 1, 'min_range' => 1,'max_range' => 100] ];
        $page = isset($page) ? filter_var($page, FILTER_VALIDATE_INT, $page_options) : 1;
        $people = (new People())->getAll($page, $order_by);
        return $this->templates->render('people::browse', ['people'=>$people,'page'=>$page, 'order_by'=>$order_by]);
    }

    public function getProfile($id) {
        $exists = (new People())->usernameExists($id);
        if($exists) {
            return $this->templates->render(
                'people::view', [
                'username' => $id,
                'data' => (new People())->getProfile($id)
                ]);
        }
        else {
            $this->app->redirect('/');
        }
    }
}