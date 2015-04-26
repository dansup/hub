<?php 

$app->get('/', function () use ($templates) {
    echo $templates->render('home');
});

$app->post('/comment/:type/add', function ($type) use ($app, $templates) {
        $types = ['node', 'meshlocal', 'people', 'service'];
        if(!in_array($type, $types)) {
            $app->redirect('/');
        }
        echo (new \App\Controllers\CommentController($templates))->postNew($type);
});

$app->get('/docs', function () use ($templates) {
        echo $templates->render('docs::home');
});
$app->get('/docs/', function () use ($app) {
        $app->redirect('/docs');
});
$app->get('/docs/demo', function () use ($templates) {
        echo $templates->render('docs::site/demo',
            ['source' => '/assets/md/demo.md']);
});

$app->get('/docs/api/v0', function () use ($templates) {
        echo $templates->render('docs::render', [
            'source' => '/assets/docs/api/v0.md',
            'base_page' => 'API Docs',
            'base_url' => '/docs/api'
            ]);
});

$app->get('/docs/help', function () use ($templates) {
        echo $templates->render('docs::render', [
            'source' => '/assets/docs/site/help.md',
            'base_page' => 'Site',
            'base_url' => '/docs/site'
            ]);
});

$app->get('/docs/faq', function () use ($templates) {
        echo $templates->render('docs::render', [
            'source' => '/assets/docs/site/faq.md',
            'base_page' => 'Site',
            'base_url' => '/docs/site'
            ]);
});

$app->get('/docs/federation', function () use ($templates) {
        echo $templates->render('docs::render', [
            'source' => '/assets/docs/site/federation.md',
            'base_page' => 'Site',
            'base_url' => '/docs/site'
            ]);
});

$app->get('/docs/contribute', function () use ($templates) {
        echo $templates->render('docs::render', [
            'source' => '/assets/docs/site/contribute.md',
            'base_page' => 'Site',
            'base_url' => '/docs/site'
            ]);
});

$app->get('/docs/source', function () use ($templates) {
        echo $templates->render('docs::render', [
            'source' => '/assets/docs/site/source.md',
            'base_page' => 'Site',
            'base_url' => '/docs/site'
            ]);
});

$app->get('/docs/site/tos', function () use ($templates) {
        echo $templates->render('docs::render', [
            'source' => '/assets/docs/site/tos.md',
            'base_page' => 'Site',
            'base_url' => '/docs/site'
            ]);
});

$app->get('/docs/developers', function () use ($templates) {
        echo $templates->render('docs::render', [
            'source' => '/assets/docs/site/developers.md',
            'base_page' => 'Site',
            'base_url' => '/docs/site'
            ]);
});

$app->get('/docs/api/embed', function () use ($templates) {
        echo $templates->render('docs::render', [
            'source' => '/assets/docs/api/embed.md',
            'base_page' => 'Site',
            'base_url' => '/docs/site'
            ]);
});

$app->get('/maps', function () use ($app, $templates) {
        echo $templates->render('maps::home');
});

$app->get('/maps/meshlocals', 'clearnetWarning', function () use ($app, $templates) {
        echo $templates->render('maps::meshlocals');
});

$app->get('/maps/network', function () use ($app, $templates) {
        echo $templates->render('maps::network');
});

$app->get('/meshlocals', function () use ($app, $templates) {
        echo $templates->render('meshlocal::home');
});
$app->get('/meshlocals/browse', function () use ($app, $templates) {
         echo (new \App\Controllers\MeshlocalController($templates))->getDirectory();
});
$app->get('/meshlocals/features', function () use ($app, $templates) {
        echo $templates->render('meshlocal::features');
});
$app->get('/meshlocals/near-me', function () use ($app, $templates) {
        echo $templates->render('meshlocal::nearme');
});
$app->get('/ml/:mid', function ($mid) use ($app, $templates) {
        echo (new \App\Controllers\MeshlocalController($templates))->redirectId($mid);
});
$app->get('/meshlocals/v/:mid/:slug', function ($mid, $slug) use ($app, $templates) {
        echo (new \App\Controllers\MeshlocalController($templates))->getProfile($mid, $slug);
});
$app->map('/meshlocals/new', 'auth', function() use($app, $templates) {
        echo (new \App\Controllers\MeshlocalController($templates))->postNew();
})->via('GET', 'POST');

$app->get('/net/stats', function () use ($app,$templates) {
        echo (new \App\Controllers\NetworkController($templates))->getNetworkStats();
});

$app->get('/nodes', function () use ($templates) {
        echo (new \App\Controllers\Node($templates))->getAllNodes();
});


$app->get('/node/:ip', function ($ip) use ($templates) {
        echo (new \App\Controllers\Node($templates))->getNode($ip);
});

$app->get('/node/:ip/peers', function ($ip) use ($templates) {
        echo (new \App\Controllers\Node($templates))->getNodePeers($ip);
});

$app->get('/node/pubkey/:key', function($key) use($templates) {
        echo $templates->render('node::pubkey', ['key' => $key]);
});

$app->map('/me', function () use ($templates) {
        /* DEPRECIATED */
        echo (new \App\Controllers\Node($templates))->getMyNode();
})->via('GET','POST');

$app->get('/people', function() use ($templates) {
        echo $templates->render('people::home');
});

$app->get('/people/browse', function() use ($templates) {
        echo (new \App\Controllers\PeopleController($templates))->getDirectory();
});

$app->get('/people/u/@:id', function($id) use ($templates) {
        echo (new \App\Controllers\PeopleController($templates))->getProfile($id);
});

$app->get('/search', function () use ($templates) {
        echo (new \App\Controllers\SearchController($templates))->getResults();
});

$app->get('/services', function () use ($templates) {
        echo (new \App\Controllers\ServiceController($templates))->getAllServices();
});

$app->get('/service/:id', function ($id) use ($templates) {
        echo (new \App\Controllers\ServiceController($templates))->getService($id);
})->conditions([':id' => '/^(\{)?[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}(?(1)\})$/i']);

$app->get('/user/node', 'auth', function() use ($app) {
        $ip = padIPV6($app->request->getIp());
        $app->redirect('/node/'.$ip);
});

$app->map('/user/settings', 'auth', function() use ($templates) {
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

$app->get('/user/notifications', 'auth', function() use ($templates) {
        echo $templates->render('user::notifications');
});

$app->map('/user/settings/node', 'auth', function() use ($templates) {
        echo (new \App\Controllers\UserController($templates))->getUserSettings();
})->via('GET','POST');

$app->get('/user/settings/notifications', 'auth', function() use ($templates) {
        echo $templates->render('user::settings/notification');
});

$app->map('/site/clearnet-confirm', function() use ($app, $templates) {
        if($app->request->isPost() == true) {
            $_SESSION['clearnet'] = true;
            // FIXME
            $ref = $app->request->get('ref');
            $app->redirect("//".APP_URL."$ref");
        }
        echo $templates->render('site::clearnet-confirm');
})->via('GET', 'POST');

$app->map('/api/v0/meshmap/nodes.json', function() use ($app) {
        $app->response->headers->set('Content-Type', 'application/json;charset=utf-8');
        echo (new \App\Models\Meshmap)->getPoints();
})->via('GET', 'POST');

$app->map('/api/v0/node/info/:ip.json', function($ip) use ($app) {
        $app->response->headers->set('Content-Type', 'application/json;charset=utf-8');
        $json = ( $app->request->get('pretty') === 'true' ) ? JSON_PRETTY_PRINT : null;
        $fresh = ( $app->request->get('flush') === 'true' ) ? false : true;
        $ip = filter_var(padIPV6($ip), FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
         echo json_encode( (new \App\Controllers\ApiController())->getV0NodeInfo($ip, $fresh), $json);
    })->via('GET', 'POST');

$app->map('/api/v0/node/feed/:ip.rss', function($ip) use ($app) {
        $app->response->headers->set('Content-Type', 'application/rss+xml; charset=utf-8');
         echo (new \App\Controllers\RSSController())->getNodeFeed($ip);
    })->via('GET', 'POST');

$app->map('/api/v0/oauth', function() {
         echo (new \App\Controllers\OauthController())->authorize();
    })->via('GET', 'POST');

$app->map('/api/v0/oauth/success', function() {
         echo (new \App\Controllers\OauthController())->authorizeSuccess();
    })->via('GET', 'POST');

$app->map('/auth/register', 'noAuth', function() use ($templates) {
            echo 'User Registration is not yet available. Sorry!';
         //echo (new \App\Controllers\UserController($templates))->newUserRegister();
    })->via('GET', 'POST');

$app->map('/auth/login', 'noAuth', function() use ($templates) {
         echo (new \App\Controllers\UserController($templates))->userLogin();
    })->via('GET', 'POST');

$app->map('/auth/socialnode', function() use ($app, $templates) {
         echo (new \App\Controllers\OauthController($templates, 'auth/socialnode'))->authorize();
    })->via('GET', 'POST');

$app->map('/auth/cb/socialnode', function() use ($app, $templates) {
         echo (new \App\Controllers\OauthController($templates, 'home'))->authorizeSuccess();
    })->via('GET', 'POST');

$app->map('/auth/socialnode/join', 'preCsrf', function() use ($templates) {
         echo (new \App\Controllers\UserController($templates))->oauthUserRegister();
    })->via('GET', 'POST');

$app->get('/site/logout', function() use ($app) {
    $_SESSION['clearnet'] = false;
    $_SESSION['logged_in'] = false;
    $_SESSION['session_since'] = false;
    $_SESSION = [];
    session_regenerate_id();
    $app->redirect('/');
});

$app->get('/site/features', function() use ($templates) {
        echo $templates->render('site::features');
});

$app->get('/site/about', function() use ($templates) {
        echo $templates->render('site::about');
});

$app->get('/site/source', function() use ($templates) {
        echo $templates->render('site::source');
});

$app->get('/site/news', function() use ($templates) {
        echo $templates->render('site::news');
});

$app->get('/site/api', function() use ($templates) {
        echo $templates->render('site::api');
});

$app->get('/site/report', function() use ($templates) {
        echo $templates->render('site::report');
});

$app->get('/site/help', function() use ($templates) {
        echo $templates->render('site::help');
});

$app->notFound(function () use ($app, $templates) {
        echo $templates->render('404');
});

$app->error(function (\Exception $e) use ($app, $templates) {
        echo $templates->render('500');
});

$app->get('/user/home', function() use ($app,$templates) {
        $app->redirect('/user/dashboard');
});
$app->get('/user/dashboard', function() use ($templates) {
        echo $templates->render('user::dashboard');
});