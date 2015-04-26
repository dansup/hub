<?php

use App\Config\App;
use App\Utils\Csrf;
use App\Models\CjdnsApi;
use App\Models\Comment;
use App\Models\Event as HubEvent;
use App\Models\Node;
use App\Models\Notification;
use App\Models\Search;
use App\Models\Service;
use League\Event\Emitter;
use League\Event\Event;

function padIPV6($address) {
            $ipparts = explode('::', $address, 2);

            $head = $ipparts[0];
            $tail = isset($ipparts[1]) ? $ipparts[1] : array();

            $headparts = explode(':', $head);
            $ippad = array();
            foreach ($headparts as $val)
            {
                $ippad[] = str_pad($val, 4, '0', STR_PAD_LEFT);
            }
            if (count($ipparts) > 1)
            {
                $tailparts = explode(':', $tail);
                $midparts = 8 - count($headparts) - count($tailparts);

                for ($i=0; $i < $midparts; $i++)
                {
                    $ippad[] = '0000';
                }

                foreach ($tailparts as $val)
                {
                    $ippad[] = str_pad($val, 4, '0', STR_PAD_LEFT);
                }
            }

            return implode(':', $ippad);
}

$emitter = new Emitter;
$csrf = new Csrf;
$search = new Search;
$allowed_admins = ['fcec:ae97:8902:d810:6c92:ec67:efb2:3ec5', 'fcfe:f4ce:609f:434b:aa44:6ea0:ebc2:2d89', 'fc15:e424:25fe:2a95:b512:ecbd:0775:832d'];
if(!in_array(padIPV6($_SERVER['REMOTE_ADDR']), $allowed_admins)) {
    header('Location: http://hub.hyperboria.net');
    die;
}

$app = new \Slim\Slim(
    [
    'debug' => true,
    'memcached_enabled' => false
]);

session_start();

$templates = new League\Plates\Engine(__DIR__.'/Views');
$templates->addFolder('base', __DIR__.'/Views');
$templates->addFolder('auth', __DIR__.'/Views/auth');
$templates->addFolder('docs', __DIR__.'/Views/docs');
$templates->addFolder('maps', __DIR__.'/Views/maps');
$templates->addFolder('meshlocal', __DIR__.'/Views/meshlocal');
$templates->addFolder('node', __DIR__.'/Views/node');
$templates->addFolder('people', __DIR__.'/Views/people');
$templates->addFolder('service', __DIR__.'/Views/service');
$templates->addFolder('site', __DIR__.'/Views/site');
$templates->addFolder('user', __DIR__.'/Views/user');
$templates->loadExtension(new League\Plates\Extension\URI($app->request->getPath()));
//$templates->loadExtension(new League\Plates\Extension\Asset( __DIR__.'/../public/', true));


$templates->registerFunction('prettyNull', function ($string) {
    $string = ($string == null ) ? 'Unknown' : $string;
    return $string;
});
$templates->registerFunction('serviceType', function ($integer) {
    switch ($integer) {
        case 1:
            $type = 'website';
            break;
        case 2:
            $type = 'ircd';
            break;
        case 3:
            $type = 'mail server';
            break;
        case 4:
            $type = 'p2p';
            break;
        case 5:
            $type = 'other/unknown';
            break;
        
        default:
            $type = 'Unknown';
            break;
    }
            return $type;
});
$templates->registerFunction('cjdnsLatest', function ($string) {
    $string = ($string == CJDNS_LATEST ) ? $string.' - Latest Version' : $string;
    return $string;
});
function auth() {
    $state = ( ( new \App\Models\User() )->auth() ) ? true : false;
    if(!$state) {
        $app = \Slim\Slim::getInstance();
        $req = $app->request;
        $ref = $req->getReferrer();
        $app->redirect("/auth/login?r={$ref}");
    }
}
function noAuth() {
    $state = ( ( new \App\Models\User() )->auth() ) ? true : false;
    if($state) {
        $app = \Slim\Slim::getInstance();
        $req = $app->request;
        $ref = $req->getReferrer();
        $app->redirect("$ref");
    }
}
function preCsrf() {
    $csrf = new \App\Utils\Csrf;
    $token_id = $csrf->get_token_id();
    $token_value = $csrf->get_token($token_id);
}
function clearnetWarning() {
    $app = \Slim\Slim::getInstance();
    if(isset($_SESSION['clearnet']) && $_SESSION['clearnet'] !== true) {
        $ref = $app->request->getResourceUri();
        $app->redirect("/site/clearnet-confirm?ref=$ref");
    }
    elseif(!isset($_SESSION['clearnet'])) {
        $_SESSION['clearnet'] = false;
        $ref = $app->request->getResourceUri();
        $app->redirect("/site/clearnet-confirm?ref=$ref");
    }
}