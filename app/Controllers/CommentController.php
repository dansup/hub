<?php
  /* 
    *   Hub
    *   Comment Controller 
    *
    */
namespace App\Controllers;

class CommentController {

    private $templates;
    private $app;

    public function __construct(\League\Plates\Engine $templates)
    {
        $this->templates = $templates;
        $this->app = \Slim\Slim::getInstance();
    }

    public function postNew($type) {
            $csrf = new \App\Utils\Csrf;
            $form_names = $csrf->form_names(array('body', 'id'), false);
        
            $req = $this->app->request;
            $identifier = $req->post($form_names['id']);
            $spam_protect = (isset($_SESSION['last_comment']) && (time() - $_SESSION['last_comment']) > getenv("COMMENT_TIMEOUT"));
            if(isset($_SESSION['last_comment']) && $spam_protect == false) {
                $this->app->redirect('/docs/help#comments-acceptable-comment-usage');
            }
            if(!empty($req->post($form_names['body']) )) {
                    if($csrf->check_valid('post')) {
                            $comment_body = htmlentities(strip_tags($req->post($form_names['body'])));
                            $comment_body = (mb_strlen($comment_body) > 140) ? mb_substr($comment_body, 0, 140) : $comment_body;
                            if((new \App\Models\User())->auth()) {
                                $author_id = (new \App\Models\User())->getUserId();
                                $author_username = (new \App\Models\User())->getUsername();
                                $author_type = 'user';
                            }
                            else {
                            $author_id = padIPV6($req->getIp());
                            $author_type = 'node';
                            $author_username = false;
                            }
                            switch ($type) {
                                case 'node':
                                   $identifier = padIPV6($identifier);
                                   $activity_type = 'node_comment';
                                   $id_type = 'node';
                                   $redirect = '/node/'.$identifier;
                                   $obj_meta = ($author_username) ? $author_username : null;
                                    break;
                                case 'meshlocal':
                                   $activity_type = 'meshlocal_comment';
                                   $id_type = 'meshlocal';
                                   $redirect = '/ml/'.$identifier;
                                   $obj_meta = ($author_username) ? $author_username : null;
                                    break;
                                
                                default:
                                   return false;
                                    break;
                            }
                            (new \App\Models\Activity())->newAction(
                                $activity_type, 
                                $author_id, 
                                $author_type,
                                $identifier, 
                                $id_type,
                                'Added a new comment.', 
                                $comment_body,
                                $obj_meta);
                            if($identifier !== $author_id) {
                                (new \App\Models\Notification())->create(
                                $identifier,
                                $author_id,
                                $activity_type);
                            }
                            $_SESSION['last_comment'] = time();
                            $_SESSION['comment_count'] = (isset($_SESSION['comment_count'])) ? $_SESSION['comment_count']++ : 1;
                            $this->app->redirect($redirect);
                    }
                    else {
                        $this->app->halt(400, 'Invalid or missing CSRF token.');
                    }
                    $form_names = $csrf->form_names(array('body', 'id'), true);
            }
            else{
                            $this->app->redirect('/node/'.$identifier);
            }
    }
}