<?php

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

session_start();

$templates = new League\Plates\Engine(__DIR__.'/Views');
$templates->addFolder('base', __DIR__.'/Views');
$templates->addFolder('maps', __DIR__.'/Views/maps');
$templates->addFolder('meshlocal', __DIR__.'/Views/meshlocal');
$templates->addFolder('node', __DIR__.'/Views/node');
$templates->addFolder('people', __DIR__.'/Views/people');
$templates->addFolder('service', __DIR__.'/Views/service');
$templates->addFolder('site', __DIR__.'/Views/site');
$templates->addFolder('user', __DIR__.'/Views/user');
$templates->loadExtension(new League\Plates\Extension\URI($app->request->getPath()));


$templates->registerFunction('prettyNull', function ($string) {
    $string = ($string == null ) ? 'Unknown' : $string;
    return $string;
});

$templates->registerFunction('cjdnsLatest', function ($string) {
    $string = ($string == CJDNS_LATEST ) ? $string.' - Latest Version' : $string;
    return $string;
});

function preCsrf() {
    $csrf = new \App\Utils\Csrf();
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