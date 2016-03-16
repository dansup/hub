<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
  Route::get('/', 'SiteController@index');
  Route::group(['prefix' => 'nodes'], function () {
    Route::get('/', function() { return redirect()->intended('/nodes/browse'); });
    Route::get('browse', 'SiteController@browseNodes');
  });
  Route::group(['prefix' => 'about'], function () {
    Route::get('/', function() { return view('about.home'); });
    Route::get('features', function() { return view('about.features'); });
    Route::get('projects/crawler', function() { return view('about.crawler'); });
    Route::get('projects/wot.json', function() { return view('about.wot'); });
    Route::get('projects/nodeinfo.json', function() { return view('about.nodeinfo'); });
  });
  Route::group(['prefix' => 'docs'], function () {
    Route::get('/', 'DocController@home');
  });
  Route::get('services', function() { return redirect()->intended('service'); });
  Route::group(['prefix' => 'service'], function () {
    Route::get('/', 'ServiceController@home');
    Route::get('add', 'ServiceController@addNew');
    Route::post('add', 'ServiceController@storeNew');
  });
  Route::group(['prefix' => 'node'], function () {
    Route::get('search/{frag}', function($frag) {
      $frag = filter_var($frag);
      $node = \App\Hub\Node\Node::where('addr', 'like', '%'.$frag.'%')->first();
      if($node!=null) {
        return redirect()->intended($node->buildNodeUrl());
      } else {
        return redirect()->intended('/');
      }
    });
    Route::group(['prefix' => 'ip'], function () {
      Route::get('{ip}', 'NodeController@show');
      Route::get('{ip}/services', 'NodeController@showServices');
      Route::get('{ip}/peers', 'NodeController@showPeers');
      Route::get('{ip}/peer-graph', 'NodeController@showPeerGraph');
      Route::get('{ip}/abuse-reports', 'NodeController@showWIP');
      Route::get('{ip}/nodeinfo', 'NodeController@showWIP');
      Route::get('{ip}/wot', 'NodeController@showWIP');
      Route::get('{ip}/stats', 'NodeController@showWIP');
      Route::get('{ip}/feed.rss', 'NodeController@showWIP');
      Route::post('{ip}/status/post', 'NodeController@addComment');
    });
  });
  Route::group(['prefix'=>'api'], function() {
    Route::match(['HEAD','POST'],'session/heartbeat', function() {
      return response()->json(['auth'=>\Auth::check()]);
    });
    Route::group(['middleware' => 'throttle:60,1'], function() {
      Route::match(['HEAD','POST','GET'],'1/comment/post', 'ApiController@createComment');
    });
  });
});
Route::group(['middleware' => 'web'], function () {
  Route::auth();

  Route::get('/home', 'HomeController@index');
});

Route::get('api/v1/site/general/stats.json', 'StatController@v1GeneralStats');
Route::get('api/v1/node/{ip}/peers.json', 'ApiController@v1NodePeers');
Route::get('api/v1/node/{ip}/peergraph.json', 'ApiController@v1NodePeerGraph');