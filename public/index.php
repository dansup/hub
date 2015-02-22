<?php
require_once(__DIR__.'/../vendor/autoload.php');
include_once(__DIR__.'/../app/config/app.php');
include_once(__DIR__.'/../app/classes/Node.php');
include_once(__DIR__.'/../app/classes/Search.php');
include_once(__DIR__.'/../app/classes/Service.php');
$app = new \Slim\Slim();
$app->add(new \Slim\Middleware\SessionCookie(array(
    'expires' => '20 minutes',
    'path' => '/',
    'domain' => null,
    'secure' => false,
    'httponly' => false,
    'name' => 'slim_session',
    'secret' => 'SECRET HERE',
    'cipher' => MCRYPT_RIJNDAEL_256,
    'cipher_mode' => MCRYPT_MODE_CBC
)));
$templates = new League\Plates\Engine(__DIR__.'/../app/views');
$templates->addFolder('base', __DIR__.'/../app/views');
$templates->addFolder('node', __DIR__.'/../app/views/node');

/* INDEX */
$app->get(
    '/',
    function () use ($templates) {
    // Render a template
    echo $templates->render('home');
    }
);
/* END INDEX */

/* NODES */

// Browse Nodes
$app->get(
    '/browse',
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
    '/node/:ip', function ($ip) use ($app, $templates, $node) {
    // Render a template
    $node_data = (array) $node->get($ip);
    $node_lgraph = json_encode($node->getLatencyGraph($ip));
    $node_peers = (array) $node->getPeers($ip);
    echo $templates->render('node::view', ['ip' => $ip, 'node'=>$node_data, 'lgraph'=>$node_lgraph, 'node_peers'=>$node_peers]);
    }
);

/* END NODES */

/* SEARCH */

$app->get(
    '/search',
    function () use ($app,$templates,$search) {
    //t=node&l=en-US&oi=&ts=&
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
    function () use ($app,$templates, $service) {
    $page = (isset($_REQUEST['page']) && intval($_REQUEST['page'])) ? intval($_GET['page']) : 1;
    $ob = (isset($_REQUEST['ob']) && intval($_REQUEST['ob'])) ? intval($_GET['ob']) : 1;
    echo $templates->render('services', ['service'=>$service,'page'=>$page, 'order_by'=>$ob]);
    }
);

/* END SERVICES */

$app->run();