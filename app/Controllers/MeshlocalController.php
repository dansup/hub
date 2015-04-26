<?php
  /* 
    *   Hub
    *   Meshlocal Controller 
    *
    */
namespace App\Controllers;

use App\Models\Meshlocal;

class MeshlocalController {

    private $templates;
    private $app;

    public function __construct(\League\Plates\Engine $templates)
    {
        $this->templates = $templates;
        $this->app = \Slim\Slim::getInstance();
    }

    public function redirectId($id) {
        $idopts = array(
            'options' => array(
                'min_range' => 1000,
                'max_range' => 2000 )
        );
        $id = filter_var($id, FILTER_VALIDATE_INT, $idopts);
        if($id){
            $ml = (new Meshlocal())->redirectId($id);
            if($ml) {
                $this->app->redirect('/meshlocals/v/'.$id.'/'.$ml);
            }
            else {
                $this->app->redirect('/meshlocals');
            }
        } else {
            $this->app->redirect('/meshlocals');
        }
    }
    public function getDirectory() {
        $order_by = $this->app->request->params('ob');
        $page = $this->app->request->params('page');
        $order_by_options = ['options' => ['default' => 1,'min_range' => 1,'max_range' => 6] ];
        $order_by = isset($order_by) ? filter_var($order_by, FILTER_VALIDATE_INT, $order_by_options) : 1;
        $page_options = ['options' => ['default' => 1, 'min_range' => 1,'max_range' => 100] ];
        $page = isset($page) ? filter_var($page, FILTER_VALIDATE_INT, $page_options) : 1;
        $meshlocal = (new Meshlocal())->getAll($page, $order_by);
        return $this->templates->render('meshlocal::browse', ['meshlocal'=>$meshlocal,'page'=>$page, 'order_by'=>$order_by]);
    }
    public function postNew() {
        $meshlocal = new \App\Models\Meshlocal();
        $csrf = new \App\Utils\Csrf();
        $req = $this->app->request;
        $ip = padIPV6($req->getIp());
        $formvars = ['name', 'city', 'state', 'country', 'lat', 'lng', 'bio'];
        $form_names = $csrf->form_names($formvars, false);
        if($this->app->request->isPost() == true) {
            if($csrf->check_valid('post')) {
                $p = $this->app->request->post();
                $name = (!empty($p[$form_names['name']])) ? filter_var($p[$form_names['name']]) : null;
                $location = null;
                $bio = (!empty($p[$form_names['bio']])) ? filter_var($p[$form_names['bio']]) : null;
                $res = (new \App\Models\Meshlocal())->createNew($name, $location, $bio, $ip);
                if($res !== false) {
                    (new \App\Models\Activity())->newAction(
                                'created_meshlocal', 
                                $ip, 
                                $res, 
                                'Created a new Meshlocal.');
                    $this->app->redirect($res);
                }
                else {
                    $this->app->redirect('/');
                    }
                }
            else {
                $this->app->halt('Invalid CSRF Token');
            }
            //$this->app->redirect('/node/'.$ip);
            $form_names = $csrf->form_names($formvars, true);
            }
        $token_id = $csrf->get_token_id();
        $token_value = $csrf->get_token($token_id);
        return $this->templates->render(
            'meshlocal::create', [
            'form_names' => $form_names,
            'token_id' => $token_id,
            'token_value' => $token_value
            ]);
    }
    public function getProfile($id, $slug) {
        $meshlocal = new \App\Models\Meshlocal();
        $profile = $meshlocal->getProfile($id, $slug);
        if($profile == false) {
            $this->app->redirect('/meshlocals');
        }
        $comment = new \App\Models\Comment();
        $csrf = new \App\Utils\Csrf();
        $token_id = $csrf->get_token_id();
        $token_value = $csrf->get_token($token_id);
        $form_names = $csrf->form_names(array('body', 'id'), false);
        $page_options = ['options' => ['default' => 1, 'min_range' => 1,'max_range' => 30] ];
        $page = isset($page) ? filter_var($page, FILTER_VALIDATE_INT, $page_options) : 1;
        $comments = $comment->get('meshlocal_comment', $id, $page);
        return $this->templates->render(
            'meshlocal::view',[
            'data' => $profile,
            'comments' => $comments,
            'form_names' => $form_names,
            'token_id' => $token_id,
            'token_value' => $token_value
            ]);
    }
}