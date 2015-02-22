<?php
require_once(__DIR__.'/../vendor/autoload.php');
include_once(__DIR__.'/../app/config/app.php');
include_once(__DIR__.'/../app/classes/Node.php');
$app = new \Slim\Slim();
$app->add(new \Slim\Middleware\SessionCookie(array(
    'expires' => '20 minutes',
    'path' => '/',
    'domain' => null,
    'secure' => false,
    'httponly' => false,
    'name' => 'slim_session',
    'secret' => 'SKADIVXCv90e45rjtk',
    'cipher' => MCRYPT_RIJNDAEL_256,
    'cipher_mode' => MCRYPT_MODE_CBC
)));
$templates = new League\Plates\Engine(__DIR__.'/../app/views');
$templates->addFolder('base', __DIR__.'/../app/views');
$templates->addFolder('node', __DIR__.'/../app/views/node');
$app->get(
    '/',
    function () use ($templates) {
    // Render a template
    echo $templates->render('home');
    }
);

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

/* SERVICES */
$app->get(
    '/services',
    function () use ($app,$templates) {
    // Render a template
    echo $templates->render('services');
    }
);


$app->run();