<?php
  /* 
    *   Hub
    *   Node Controller 
    *
    */
namespace App\Controllers;

use \Memcached;

class Node {

    private $templates;

    public function __construct(\League\Plates\Engine $templates)
    {
        $this->templates = $templates;
        $this->m = new Memcached();
        $this->m->addServer('localhost', 11211);
    }

    public function getAllNodes() {
        $app = \Slim\Slim::getInstance();
        $node = new \App\Models\Node;
        $order_by = $app->request->params('ob');
        $page = $app->request->params('page');
        $order_by_options = ['options' => ['default' => 1,'min_range' => 1,'max_range' => 6] ];
        $order_by = isset($order_by) ? filter_var($order_by, FILTER_VALIDATE_INT, $order_by_options) : 1;
        $page_options = ['options' => ['default' => 1, 'min_range' => 1,'max_range' => 100] ];
        $page = isset($page) ? filter_var($page, FILTER_VALIDATE_INT, $page_options) : 1;
        $nodes = ($this->m->get('h-nodes-all-pg'.$page.'-ob'.$order_by) !== false) ? $this->m->get('h-nodes-all-pg'.$page.'-ob'.$order_by) : $node->allKnownNodes($page, $order_by);
        return $this->templates->render('node::browse', ['nodes'=>$nodes,'page'=>$page, 'order_by'=>$order_by]);
        
    }
    public function getNode($ip) {
        $ip = padIPV6($ip);
        $app = \Slim\Slim::getInstance();
        $comment = new \App\Models\Comment;
        $node = new \App\Models\Node;
        $service = new \App\Models\Service;
        $csrf = new \App\Utils\Csrf;
        $token_id = $csrf->get_token_id();
        $token_value = $csrf->get_token($token_id);
        $form_names = $csrf->form_names(array('body', 'id'), false);

        if(mb_strlen($ip) == 54) {
            $app->redirect('/node/pubkey/'.urlencode($ip));
        }
        if($node->knownNode($ip) == false) {
            $app->redirect('/nodes');
        }
        $node_data = (array) $node->get($ip);
        $node_lgraph = json_encode($node->getLatencyGraph($ip));
        $node_vgraph = json_encode($node->getVersionGraph($ip));
        $node_peers = ( $node->getPeers($ip) == false ) ? false : $node->getPeers($ip);
        $node_services = (array) $service->getOwnedServices($ip);
        $page = $app->request->params('p');
        $page_options = ['options' => ['default' => 1, 'min_range' => 1,'max_range' => 30] ];
        $page = isset($page) ? filter_var($page, FILTER_VALIDATE_INT, $page_options) : 1;
        $comments = $comment->get('node_comment', $ip, $page);
        return $this->templates->render('node::view', [
            // Todo: Ajax nodeinfo, graph, peer, service and comment data.
            'ip' => $ip, 
            'node' => $node_data, 
            'node_peers' => $node_peers,
            'comments' => $comments,
            'node_lgraph' => $node_lgraph,
            'node_vgraph' => $node_vgraph,
            'form_names' => $form_names,
            'token_id' => $token_id,
            'token_value' => $token_value
            ]);
    }

    public function getNodePeers($ip) {
        $app = \Slim\Slim::getInstance();
        $node = new \App\Models\Node;

        if(mb_strlen($ip) == 54) {
            $app->redirect('/node/pubkey/'.urlencode($ip));
        }
        if($node->knownNode($ip) == false) {
            $app->redirect('/nodes');
        }
        $node_data = (array) $node->get($ip);
        $node_peers = ( $node->getPeers($ip) == false ) ? false : $node->getPeers($ip);
        $page = (isset($_GET['p'])) ? (int) $_GET['p'] : 1;
        return $this->templates->render('node::peers', [
            'ip' => $ip, 
            'node' => $node_data, 
            'node_peers' => $node_peers,
            ]);
    }
    public function getMyNode() {
        // DEPRECIATED
        $app = \Slim\Slim::getInstance();
        $req = $app->request;
        $ip = $req->getIp();
        $node = new \App\Models\Node;
        return $this->templates->render(
            'node::me', [
            'ip' => $ip, 
            'node'=>$node
            ]);
    }
}