<?php

/*
  * hub
  * the hyperboria network analytics machine
  * version 0.3
  *
  */

require_once(__DIR__.'/../vendor/autoload.php');

// Autoload these
require_once(__DIR__.'/../app/config/app.php');
require_once(__DIR__.'/../app/classes/CjdnsApi.php');
require_once(__DIR__.'/../app/classes/Event.php');
require_once(__DIR__.'/../app/classes/Node.php');
require_once(__DIR__.'/../app/classes/Search.php');
require_once(__DIR__.'/../app/classes/Service.php');

require_once(__DIR__.'/../app/libs/csrf.php');


use League\Event\Emitter;
use League\Event\Event;

$emitter = new Emitter;

$app = new \Slim\Slim();

$templates = new League\Plates\Engine(__DIR__.'/../app/views');
$templates->addFolder('base', __DIR__.'/../app/views');
$templates->addFolder('site', __DIR__.'/../app/views/site');
$templates->addFolder('node', __DIR__.'/../app/views/node');
$templates->addFolder('service', __DIR__.'/../app/views/service');
$templates->loadExtension(new League\Plates\Extension\URI($app->request->getPath()));

$templates->registerFunction('prettyNull', function ($string) {
    $string = ($string == null ) ? 'Unknown' : $string;
    return $string;
});
$templates->registerFunction('cjdnsLatest', function ($string) {
    $string = ($string == CJDNS_LATEST ) ? $string.' - Latest Version' : $string;
    return $string;
});

/* INDEX */
$app->get(
    '/',
    function () use ($templates) {
    echo $templates->render('home');
    }
);
/* END INDEX */

/* NODES */

// Browse Nodes
$app->get(
    '/nodes',
    function () use ($app,$templates, $node) {
    $order_by_options = ['options' => ['default' => 1,'min_range' => 1,'max_range' => 6] ];
    $order_by = isset($_GET['ob']) ? filter_var($_GET['ob'], FILTER_VALIDATE_INT, $order_by_options) : 1;
    $page_options = ['options' => ['default' => 1, 'min_range' => 1,'max_range' => 20] ];
    $page = isset($_GET['page']) ? filter_var($_GET['page'], FILTER_VALIDATE_INT, $page_options) : 1;
    echo $templates->render('node::browse', ['node'=>$node,'page'=>$page, 'order_by'=>$order_by]);
    }
);

// View Node
$app->get(
    '/node/:ip', 
    function ($ip) 
    use ($app, $templates, $node, $service, $comment, $emitter) {

    $node_data = (array) $node->get($ip);
    $node_lgraph = json_encode($node->getLatencyGraph($ip));
    $node_peers = (array) $node->getPeers($ip);
    $node_services = (array) $service->getOwnedServices($ip);
    // FIXME: app->request args for page #
    $page = 1;//(isset($_REQUEST['p'])) ? (int) $_REQUEST['p'] : 1;
    $comments = $comment->get('node', $ip, $page);
    echo $templates->render('node::view', [
        // Todo: Ajax nodeinfo, graph, peer, service and comment data.
        'ip' => $ip, 
        'node' => $node_data, 
        'lgraph' => $node_lgraph, 
        'node_peers' => $node_peers,
        'node_services' => $node_services,
        'comment' => $comments
        ]);
    ((time() - strtotime($node_data['last_seen'])) > 300)
    ? $node->pingNode($ip, $_SERVER['SERVER_ADDR']) : null;
    }
)->conditions([':ip' => '/^(((?=(?>.*?(::))(?!.+3)))3?|([dA-F]{1,4}(3|:(?!$)|$)|2))(?4){5}((?4){2}|(25[0-5]|(2[0-4]|1d|[1-9])?d)(.(?7)){3})z/i']);
);

// View My Node
$app->map('/me', function () use ($templates, $node, $csrf) {
    echo $templates->render('node::me', ['ip' => $_SERVER['REMOTE_ADDR'], 'node'=>$node, 'csrf'=> $csrf]);
    })->via('GET','POST');

/* END NODES */

/* SEARCH */

$app->get(
    '/search',
    function () use ($app,$templates,$search) {
    $types = ['node','service','people'];
    $query = (isset($_REQUEST['q']) && !empty($_REQUEST['q'])) ? filter_var($_REQUEST['q']) : null;
    $page = (isset($_REQUEST['p']) && intval($_REQUEST['p'])) ? intval($_GET['p']) : 1;
    $type = (isset($_REQUEST['t']) && !empty($_REQUEST['t']) && in_array($_REQUEST['t'], $types)) ? filter_var($_REQUEST['t']) : 'node';
    $show_results = ($query && strlen($query) > 2) ? true : false;
    echo $templates->render('search', ['search'=>$search,'ip'=>$_SERVER['REMOTE_ADDR'],'query'=>$query, 'type'=>$type, 'page'=>$page, 'show_results'=>$show_results, 'lang'=>'en-US','timestamp'=>time()]);
    }
);

/* END SEARCH */

/* SERVICES */

$app->get(
    '/services',
    function () use ($app, $templates, $service) {
    $page = (isset($_REQUEST['page']) && intval($_REQUEST['page'])) ? intval($_GET['page']) : 1;
    $ob = (isset($_REQUEST['ob']) && intval($_REQUEST['ob'])) ? intval($_GET['ob']) : 1;
    echo $templates->render('services', ['service'=>$service,'ip'=>$_SERVER['REMOTE_ADDR'],'page'=>$page, 'order_by'=>$ob]);
    }
);

/* END SERVICES */

$app->run();