<?php 

/* INDEX */
$app->get(
    '/',
    function () use ($templates) {
    echo $templates->render('home');
    }
);
/* END INDEX */

/* COMMENTS */

$app->post(
    '/comment/add',
    function () use ($app) {
        $comment = new \App\Models\Comment;
        $csrf = new \App\Utils\Csrf;
        $form_names = $csrf->form_names(array('body', 'id'), false);
    
        $req = $app->request;
        $identifier = $req->post($form_names['id']);
        if(!empty($req->post($form_names['body']) )) {
                // Check if token id and token value are valid.
                if($csrf->check_valid('post')) {
                        // Get the Form Variables.
                        $author_body = $req->post($form_names['body']);
         
                        $author_ip = $req->getIp();
                        $comment->add('node', $identifier, $author_ip, $author_body);
                        $app->flash('info', 'Comment successfully addded.');
                        $app->redirect('/node/'.$identifier);
                }
                else {
                    $app->halt(400, 'Invalid or missing CSRF token.');
                }
                // Regenerate a new random value for the form.
                $form_names = $csrf->form_names(array('user', 'password'), true);
        }
        else{
                        $app->redirect('/node/'.$identifier);
        }
    }
);
/* END COMMENTS */

/* MAPS */

$app->get(
    '/maps',
    function () 
    use ($app, $templates) {
        echo $templates->render(
            'maps::home');
    }
);

$app->get(
    '/maps/meshlocals',
    'clearnetWarning',
    function () 
    use ($app, $templates) {
        echo $templates->render(
            'maps::meshlocals');
    }
);

$app->get(
    '/maps/network',
    function () 
    use ($app, $templates) {
        echo $templates->render(
            'maps::network');
    }
);

/* END MAPS */

/* MESHLOCALS */

$app->get(
    '/meshlocals',
    function () 
    use ($app, $templates) {
        echo $templates->render(
            'meshlocal::home');
});

$app->map(
    '/meshlocals/new',
    function()
    use($app, $templates) {
        $meshlocal = new \App\Models\Meshlocal();
        $csrf = new \App\Utils\Csrf();
        $ip = $app->request->getIp();
        $formvars = ['name', 'city', 'state', 'country', 'lat', 'lng', 'bio'];
        $form_names = $csrf->form_names($formvars, false);
        if($app->request->isPost() == true) {
            if($csrf->check_valid('post')) {
                $p = $app->request->post();
                $name = (!empty($p[$form_names['name']])) ? filter_var($p[$form_names['name']]) : null;
                }
            else {
                die('Invalid CSRF Token');
            }
            //$app->redirect('/node/'.$ip);
            $form_names = $csrf->form_names($formvars, true);
            }
        $token_id = $csrf->get_token_id();
        $token_value = $csrf->get_token($token_id);
        echo $templates->render(
            'meshlocal::create', [
            'form_names' => $form_names,
            'token_id' => $token_id,
            'token_value' => $token_value
            ]);

})->via('GET', 'POST');

/* END MESHLOCALS */

/*NETWORK STATS */

$app->get(
    '/net/stats',
    function () use ($app,$templates) {
    $stats = new \App\Models\Stats;
    $total = $stats->getTotalNodes();
    $avg_ver = $stats->getAverageVersion();
    $avg_lat = $stats->getAverageLatency();
    echo $templates->render('base::stats', [
        'total'=>$total,
        'avg_ver' => $avg_ver,
        'avg_lat' => $avg_lat
        ]);
    }
);

/* END NETWORK STATS */

/* NODES */

// Browse Nodes
$app->get(
    '/nodes',
    function () use ($app,$templates) {
    $node = new \App\Models\Node;
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
    use ($app, $templates, $emitter) {
    $ip = padIPV6($ip);
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
    $node_lgraph = $node->getLatencyGraph($ip);
    $node_peers = ( $node->getPeers($ip) == false ) ? false : $node->getPeers($ip);
    $node_services = (array) $service->getOwnedServices($ip);
    // FIXME: app->request args for page #
    $page = (isset($_GET['p'])) ? (int) $_GET['p'] : 1;
    $comments = $comment->get('node', $ip, $page);
    echo $templates->render('node::view', [
        // Todo: Ajax nodeinfo, graph, peer, service and comment data.
        'ip' => $ip, 
        'node' => $node_data, 
        'node_peers' => $node_peers,
        'comments' => $comments,
        'form_names' => $form_names,
        'token_id' => $token_id,
        'token_value' => $token_value
        ]);
    }
);
$app->get(
    '/node/:ip/peers',
    function ($ip) 
    use ($app, $templates, $emitter) {
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
    echo $templates->render('node::peers', [
        'ip' => $ip, 
        'node' => $node_data, 
        'node_peers' => $node_peers,
        ]);
    }
);
// PubKey
$app->get(
    '/node/pubkey/:key',
    function($key) 
    use($app, $templates) {
        echo $templates->render('node::pubkey', ['key' => $key]);
    }
);
// View My Node
$app->map('/me', function () use ($templates) {
    $node = new \App\Models\Node;
    echo $templates->render(
        'node::me', [
        'ip' => $_SERVER['REMOTE_ADDR'], 
        'node'=>$node]
        );
    })->via('GET','POST');

/* END NODES */

/* PEOPLE */

// People Home
$app->get(
    '/people',
    function()
    use ($app, $templates) {
        echo $templates->render(
            'people::home');
});

/* END PEOPLE */

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
    function () use ($app, $templates) {
    $service = new \App\Models\Service;
    $page = (isset($_REQUEST['page']) && intval($_REQUEST['page'])) ? intval($_GET['page']) : 1;
    $ob = (isset($_REQUEST['ob']) && intval($_REQUEST['ob'])) ? intval($_GET['ob']) : 1;
    echo $templates->render('service::browse', ['service'=>$service,'ip'=>$_SERVER['REMOTE_ADDR'],'page'=>$page, 'order_by'=>$ob]);
    }
);

// View Service
$app->get(
    '/service/:id', function ($id) use ($app, $templates, $emitter) {
    $service = new \App\Models\Service;
    $data = $service->getService($id);
    echo $templates->render('service::view', [
        'service'=>$data
        ]);

    }
)->conditions([':id' => '/^(\{)?[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}(?(1)\})$/i']);

/* END SERVICES */

/* USER */

// User Settings
$app->get(
    '/user/node', function()
    use ($app) {
        $req = $app->request;
        $app->redirect('/node/'.$req->getIp());
});

// User Settings
$app->map(
    '/user/settings',
    function() use ($templates) {
        $csrf = new \App\Utils\Csrf;
        $token_id = $csrf->get_token_id();
        $token_value = $csrf->get_token($token_id);
        $form_names = $csrf->form_names(array('body', 'id'), false);
        echo $templates->render(
            'user::settings/general',
            [ 
            'form_names' => $form_names,
            'token_id' => $token_id,
            'token_value' => $token_value
            ]);
})->via('GET','POST');

// User Notifications
$app->get(
    '/user/notifications', function()
    use ($templates) {
        echo $templates->render('user::notifications');
});
$app->get('/logout', function() use ($app) {
    session_regenerate_id();
    $_SESSION['clearnet'] = false;
    $app->redirect('/');
});
// Node Settings
$app->map(
    '/user/settings/node', function()
    use ($app, $templates) {
        $csrf = new \App\Utils\Csrf();
        $node = new \App\Models\Node();
        $ip = $app->request->getIp();
        $formvars = ['node_hostname', 'node_ownername', 'node_country', 'node_map_privacy', 'node_lat', 'node_lng', 'node_api_enabled'];
        $form_names = $csrf->form_names($formvars, false);
        if($app->request->isPost() == true) {
            if($csrf->check_valid('post')) {
                foreach($app->request->post() as $k => $p) {
                    if(empty($p)) {
                        continue;
                    }

                switch ($k) {
                        case $form_names['node_hostname']:
                            $p = (mb_strlen($p) > 3 && mb_strlen($p) < 16) ? htmlentities($p) : null;
                            $node->postUpdate('node_hostname', $p, $ip);
                            //var_dump($k, $p);
                            break;
                        case ($form_names['node_ownername']):
                            $p = (mb_strlen($p) > 3 && mb_strlen($p) < 12) ? htmlentities($p) : null;
                            $node->postUpdate('node_ownername', $p, $ip);
                            break;
                        case ($form_names['node_country']):
                            $p = (mb_strlen($p) > 3 && mb_strlen($p) < 12) ? htmlentities($p) : null;
                            $node->postUpdate('node_country', $p, $ip);
                            break;
                        case ($form_names['node_lat']):
                            $p = floatval($p);
                            $node->postUpdate('node_lat', $p, $ip);
                            break;
                        case ($form_names['node_lng']):
                            $p = floatval($p);
                            $node->postUpdate('node_lng', $p, $ip);
                            break;
                        case ($form_names['node_map_privacy']):
                            $p = intval($p);
                            $node->postUpdate('node_map_privacy', $p, $ip);
                            break;
                        
                        default:
                            continue;
                            break;
                    }
                }
            $app->redirect('/node/'.$ip);
            $form_names = $csrf->form_names($formvars, true);
            }
            else {
                die('Invalid CSRF Token');
            }
        }
        $token_id = $csrf->get_token_id();
        $token_value = $csrf->get_token($token_id);
        $node_data = (array) $node->get($ip);
        echo $templates->render(
            'user::settings/node',
            [ 
            'node' => $node_data,
            'form_names' => $form_names,
            'token_id' => $token_id,
            'token_value' => $token_value
            ]);
})->via('GET','POST');


// Notification Settings
$app->get(
    '/user/settings/notifications', function()
    use ($templates) {
        echo $templates->render('user::settings/notification');
});


/* END USER */
$app->map(
    '/site/clearnet-confirm', function() use ($app, $templates) {
        if($app->request->isPost() == true) {
            $_SESSION['clearnet'] = true;
            // FIXME
            $ref = $app->request->get('ref');
            $app->redirect("//".APP_URL."$ref");
        }
        echo $templates->render('site::clearnet-confirm');
})->via('GET', 'POST');

$app->map(
    '/api/v0/meshmap/nodes.json',
    function() use ($app) {
        $app->response->headers->set('Content-Type', 'application/json;charset=utf-8');
        echo (new \App\Models\Meshmap)->getPoints();
    })->via('GET', 'POST');

$app->map(
    '/api/v0/node/info/:ip.json',
    function($ip) use ($app) {
        $app->response->headers->set('Content-Type', 'application/json;charset=utf-8');
        $resp = ( $app->request->get('pretty') === 'true' ) ? JSON_PRETTY_PRINT : null;
         echo json_encode( (new \App\Controllers\Api())->getV0NodeInfo($ip), $resp);
    })->via('GET', 'POST');

$app->get(
    '/site/features', function() use ($templates) {
        echo $templates->render('site::features');
});
$app->get(
    '/site/about', function() use ($templates) {
        echo $templates->render('site::about');
});
$app->get(
    '/site/source', function() use ($templates) {
        echo $templates->render('site::source');
});
$app->get(
    '/site/api', function() use ($templates) {
        echo $templates->render('site::api');
});
$app->get(
    '/site/report', function() use ($templates) {
        echo $templates->render('site::report');
});
$app->get(
    '/site/help', function() use ($templates) {
        echo $templates->render('site::help');
});

$app->notFound(function () use ($app, $templates) {
        echo $templates->render('404');
});
$app->error(function (\Exception $e) use ($app, $templates) {
        echo $templates->render('500');
});
