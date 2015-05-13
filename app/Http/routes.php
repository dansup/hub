<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'HomeController@index');

Route::group(['prefix' => 'api'], function()
{
    Route::post('v0/node/update.json',  'PeerStatsController@update'); 
    // Autocomplete v1
    Route::get('v1/node/autocomplete.json', 'NodeController@autocompleteJson');
    // Node v0 - Spec not yet finalized
    Route::get('node/{id}/info.json', function($id) {
     return response()->json([
        'response_code'=> 500,
        'error'=>true,
        'api_status' => 'depreciated',
        'error_message'=>'Endpoint not yet available. Please use the experimental v0 api.',
        'api_use_instead' => 'http://dev.hub.hyperboria.net/api/v0/node/'.$id.'/info.json',
        ], 500, [], JSON_PRETTY_PRINT); 
    });
    Route::get('v0/node/{ip}/info.json', function(){ 
     return response()->json([
        'response_code'=> 500,
        'error'=>true,
        'api_status' => 'temporarily unavailable',
        'error_message'=>'Endpoint is temporarily unavailable.',
        ], 500, [], JSON_PRETTY_PRINT); 
    });
    Route::get('v0/node/{ip}/peers.json', 'ApiController@getNodePeers');
    // Node Website APIs (not for public use)
    Route::post('web/node/update.json', 'ApiController@updateNode');
    // Map v1
    Route::get('v1/map/graph/data.json', 'MapController@sigmaJson');
    Route::get('v1/map/graph/node.json', 'MapController@graphNodeJson');
    Route::get('v1/map/graph/edge.json', 'MapController@graphEdgeJson');

});

Route::get('doc', function() { return redirect('/docs'); });

Route::group(['prefix' => 'docs'], function()
{
    Route::get('/', function() { return view('doc.home'); });
    Route::get('{category}/{path}', function($category,$path) {
        return view('doc.view', [
            'cat' => $category,
            'path' => $path,
            ]);
    });
});

Route::group(['prefix' => 'nodes'], function()
{
    Route::get('/', 'NodeController@index');
    Route::get('create', 'NodeController@create');
    Route::get('{ip}.k', 'NodeController@pubkeyredirect');
    Route::get('me', function() { 
        return redirect('/nodes/' . Request::getClientIp());
    });
    Route::get('{ip}.json', 'NodeController@nodeinfo');
    Route::get('{ip}/activity.rss', 'NodeController@activityRss2');
    Route::get('{ip}/edit', 'NodeController@edit');
    Route::get('{ip}/followers.json', 'NodeController@followersJson');
    Route::get('{ip}/followers', 'NodeController@followers');
    Route::get('{ip}/follows', 'NodeController@follows');
    Route::get('{ip}/follows.json', 'NodeController@followsJson');

    // TODO: Move to POST routes to API, no more POSTS to non-api routes
    Route::match(['get', 'post'], '{ip}/follow.json', 'NodeController@follow');
    Route::match(['get', 'post'], '{ip}/comments', 'NodeController@comments');
    Route::match(['get', 'post'], '{ip}/comment/add', 'CommentController@store');
    Route::match(['get', 'post'], '{ip}/unfollow.json', 'NodeController@unfollow');
    Route::match(['get', 'post'], '{ip}/followed.json', 'NodeController@followed');
    Route::get('{ip}/activity', [
        'as'    => 'node.activity',
        'uses'  => 'NodeController@activity'
        ]);
    Route::get('{ip}/peers', [
        'as'    => 'node.peers',
        'uses'  => 'NodeController@peers'
        ]);
    Route::get('{ip}/services', [
        'as'    => 'node.services',
        'uses'  => 'NodeController@services'
        ]);
    Route::get('{ip}', [
        'as'    => 'node.view',
        'uses'  => 'NodeController@view'
        ]);
});

Route::group(['prefix' => 'services'], function()
{
    Route::get('/', 'ServiceController@index');
    Route::get('{id}', 'ServiceController@view');
    Route::get('{id}/followers', 'ServiceController@followers');
    Route::get('{id}/follows', 'ServiceController@follows');
});

Route::group(['prefix' => 'maps'], function()
{
    Route::get('/', 'MapController@index');
    Route::get('graph', 'MapController@sigma');
});


Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
