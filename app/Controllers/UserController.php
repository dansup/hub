<?php
  /* 
    *   Hub
    *   User Controller 
    *
    */
namespace App\Controllers;

use App\Models\User;

class UserController {

    private $templates;
    private $app;

    public function __construct(\League\Plates\Engine $templates)
    {
        $this->templates = $templates;
        $this->app = \Slim\Slim::getInstance();
    }

    public function getUserSettings() {
        $csrf = new \App\Utils\Csrf();
        $node = new \App\Models\Node();
        $ip = padIPV6($this->app->request->getIp());
        $formvars = ['node_hostname', 'node_ownername', 'node_country', 'node_map_privacy', 'node_lat', 'node_lng', 'node_api_enabled'];
        $form_names = $csrf->form_names($formvars, false);
        if($this->app->request->isPost() == true) {
            if($csrf->check_valid('post')) {
                foreach($this->app->request->post() as $k => $p) {
                    if(empty($k) OR mb_strlen($k) < 3 OR empty($p) OR mb_strlen($p) < 3) {
                        continue;
                    }
                $field = null;
                switch ($k) {
                        case $form_names['node_hostname']:
                            $field = 'node_hostname';
                            $p = (mb_strlen($p) > 3 && mb_strlen($p) < 16) ? htmlentities($p) : null;
                            break;
                        case ($form_names['node_ownername']):
                            $field = 'node_ownername';
                            $p = (mb_strlen($p) > 3 && mb_strlen($p) < 12) ? htmlentities($p) : null;
                            break;
                        case ($form_names['node_country']):
                            $field = 'node_country';
                            $p = (mb_strlen($p) > 3 && mb_strlen($p) < 12) ? htmlentities($p) : null;
                            break;
                        case ($form_names['node_lat']):
                            $field = 'node_lat';
                            $p = floatval($p);
                            break;
                        case ($form_names['node_lng']):
                            $field = 'node_lng';
                            $p = floatval($p);
                            break;
                        case ($form_names['node_map_privacy']):
                            $field = 'node_map_privacy';
                            $p = intval($p);
                            break;
                        
                        default:
                            continue 2;
                            break;
                    }
                    $node->postUpdate($field, $p, $ip);
                    (new \App\Models\Activity())->newAction(
                        'node_profile_update', 
                        $ip, 
                        $ip, 
                        'Updated node info.', 
                        $p, 
                        $field);
                }
            $this->app->redirect('/node/'.$ip);
            $form_names = $csrf->form_names($formvars, true);
            }
            else {
                die('Invalid CSRF Token');
            }
        }
        $token_id = $csrf->get_token_id();
        $token_value = $csrf->get_token($token_id);
        $node_data = (array) $node->get($ip);
        return $this->templates->render(
            'user::settings/node',
            [ 
            'node' => $node_data,
            'form_names' => $form_names,
            'token_id' => $token_id,
            'token_value' => $token_value
            ]);
    }

    public function newUserRegister() {
        $csrf = new \App\Utils\Csrf();
        $ip = padIPV6($this->app->request->getIp());
        $formvars = ['username', 'email', 'password', 'password_confirm'];
        $form_names = $csrf->form_names($formvars, false);
        if($this->app->request->isPost() == true) {
            $req = $this->app->request;
            if($csrf->check_valid('post')) {
                    $permitted_chars = ["_"];
                    $username = strtolower(trim($req->post($form_names['username'])));
                    $username = (mb_strlen($username) > 4 && mb_strlen($username) < 14) ? $username : false;
                    $username = (ctype_alnum( str_replace($permitted_chars, '', $username ) )) ? $username : false;
                    $email = filter_var($req->post($form_names['email']), FILTER_VALIDATE_EMAIL);
                    $password = $req->post($form_names['password']);
                    $password_confirm = $req->post($form_names['password_confirm']);
                    $password = ($password === $password_confirm) ? $password : false;
                    if(!$username OR !$email OR !$password) {
                        $this->app->halt(500, 'Invalid Request');
                    }
                    $resp = (new User())->createNew($username, $email, $password);
                    if($resp) {
                        $this->app->redirect('/auth/login');
                    }
                    else {
                        $this->app->error('UnknownError');
                    }
                }
                else {
                    $this->app->halt(403, 'CSRF Error');
                }
            }
        $token_id = $csrf->get_token_id();
        $token_value = $csrf->get_token($token_id);
        return $this->templates->render(
            'site::register',
            [ 
            'form_names' => $form_names,
            'token_id' => $token_id,
            'token_value' => $token_value
            ]);

    }
    public function userLogin() {
        $csrf = new \App\Utils\Csrf();
        $ip = padIPV6($this->app->request->getIp());
        $formvars = ['username', 'password'];
        $form_names = $csrf->form_names($formvars, false);
        if($this->app->request->isPost() == true) {
            $req = $this->app->request;
            if($csrf->check_valid('post')) {
                $permitted_chars = ["_"];
                $username = strtolower(trim($req->post($form_names['username']))); 
                $username = (mb_strlen($username) > 1 && mb_strlen($username) < 14) ? $username : false;
                $username = (ctype_alnum( str_replace($permitted_chars, '', $username ) )) ? $username : false;
                $password = $req->post($form_names['password']);
                if(!$username OR !$password) {
                    $this->app->halt(403, 'Error');
                }
                if((new \App\Models\User())->login($username, $password, $ip)) {
                    $this->app->redirect('/');
                }
                else {
                    $this->app->redirect('/site/login');
                }
                }
                else {
                    $this->app->halt(403, 'CSRF Error');
                }
            }
        $token_id = $csrf->get_token_id();
        $token_value = $csrf->get_token($token_id);
        return $this->templates->render(
            'site::login',
            [ 
            'form_names' => $form_names,
            'token_id' => $token_id,
            'token_value' => $token_value
            ]);

    }

    public function oauthLogin($username) {
        return (new User)->oauthLogin($username, $this->app->request->getIp());
    }

    public function oauthUserRegister() {
        $csrf = new \App\Utils\Csrf();
        $username = filter_var($_SESSION['oauth_socialnode_username']);
        $ip = padIPV6($this->app->request->getIp());
        $formvars = ['email', 'password', 'password_confirm'];
        $form_names = $csrf->form_names($formvars, false);
        if($this->app->request->isPost() == true) {
            $req = $this->app->request;
            if($csrf->check_valid('post')) {
                $permitted_chars = ["_"];
                $username = strtolower(trim($username));
                $username = (mb_strlen($username) > 4 && mb_strlen($username) < 14) ? $username : false;
                $username = (ctype_alnum( str_replace($permitted_chars, '', $username ) )) ? $username : false;
                $email = filter_var($req->post($form_names['email']), FILTER_VALIDATE_EMAIL);
                $password = $req->post($form_names['password']);
                $password_confirm = $req->post($form_names['password_confirm']);
                $password = ($password === $password_confirm) ? $password : false;
                if(!$username OR !$email OR !$password) {
                    $this->app->halt(500, 'Invalid Request');
                }
                    $resp = (new User())->createNew($username, $email, $password);
                    if($resp) {
                        $this->app->redirect('/auth/login');
                    }
                    else {
                        $this->app->halt($resp);
                    }
                }
                else {
                    $this->app->halt(403, 'CSRF Error');
                }
            }
        $token_id = $csrf->get_token_id();
        $token_value = $csrf->get_token($token_id);
        return $this->templates->render(
            'auth::socialnode.join',
            [ 
            'form_names' => $form_names,
            'token_id' => $token_id,
            'token_value' => $token_value,
            'username' => $username
            ]);

    }
}
