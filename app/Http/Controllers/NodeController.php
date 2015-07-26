<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Hub\Req as Request;
use Illuminate\Support\Facades\Response;
use App\Node;
use App\Abuse;
use App\NodeBadge;
use App\Peer;
use App\Comment;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Item;

class NodeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$nodes = \App\Node::
		orderBy('updated_at', 'DESC')
		->orderBy('created_at', 'DESC')
		->paginate(25);

		return view('node.home', [
			'nodes' => $nodes,
			]);
	}

	/**
	 * Show the node.
	 *
	 * @return Response
	 */
	public function view($ip) {
		try {

			$node = Node::whereAddr($ip)->firstOrFail();

			$node->activity_count = count($node->activity);

			$peers = Peer::where('origin_ip', '=', $ip)
							->distinct()->get(['peer_key']);

			$node->abuse = Abuse::where('target_addr', '=', $ip)->get();
			//unset($peers[0]);
			$node->peers = $peers;

			$node->badges = NodeBadge::where('public_key', '=', $node->public_key)->get();

			$node->verified = ( NodeBadge::wherePublicKey($node->public_key)->whereType('system')->whereLabel('verified')->get() == false ) ? :false;
			$node->active = ( NodeBadge::wherePublicKey($node->public_key)->whereType('system')->whereLabel('active')->get() == false ) ? :false;
			$node->hasNodeInfo = ( NodeBadge::wherePublicKey($node->public_key)->whereType('system')->whereLabel('hasni')->get() == false ) ? :false;

			return view('node.view', [ 'n' => $node, ]);

		} catch (\Exception $e) {
			$request_ip = Request::ip();
			if ($ip == $request_ip) {
				/* If node is not yet in db */
			    return \redirect('node/create');
			} else {
				/* ModelNotFoundException */
	            return response()->view('errors.404');
			}
		}
	}


	public function viewStatus($ip, $id) {

		$node = Node::where('privacy_level', '>', 0)->whereAddr($ip)->firstOrFail();

		// TODO: Move to API
		$c = Comment::where('target', '=', $ip)->findOrFail([
			'id' => $id
			]);


		return view('node.status', [
			'n' => $node,
			'c' => $c[0]
			]);
	}

	public function viewWot($ip) {
		$node = Node::where('privacy_level', '>', 0)->whereAddr($ip)->firstOrFail();
		return view('node.wot', [
			'n' => $node
			]);
	}

	public function nodestats($ip) {

		$node = Node::where('privacy_level', '>', 0)->whereAddr($ip)->firstOrFail();


		return view('node.nodestats', [ 'n' => $node, ]);
	}

	public function peers($ip) {
		$node = Node::where('privacy_level', '>', 0)->whereAddr($ip)->firstOrFail();

		// TODO: Move to API
		$peers = Peer::where('origin_ip', '=', $ip)->distinct()->get(['peer_key']);
		unset($peers[0]);
		$node->peers = $peers;

		return view('node.peers', [
			'n' => $node,
			]);
	}
	public function services($ip) {
		$node = Node::where('privacy_level', '>', 0)->whereAddr($ip)->firstOrFail();

		$peers = Peer::where('origin_ip', '=', $ip)->distinct()->get(['peer_key']);
		unset($peers[0]);
		$node->peers = $peers;

		return view('node.services', [
			'n' => $node,
			]);
	}
	public function activity($ip) {

		$node = Node::where('privacy_level', '>', 0)->whereAddr($ip)->firstOrFail();

		// TODO: Move to API
		$activity = (new \Activity())
		->where('actor_user_id', '=', $ip)
		->orderBy('id', 'DESC')
		->paginate(6);

		$peers = Peer::where('origin_ip', '=', $ip)->distinct()->get(['peer_key']);
		unset($peers[0]);
		$node->peers = $peers;

		return view('node.activity', [
			'n' => $node,
			'activity' => $activity,
			]);
	}

	public function nodeinfo($ip) {

		return redirect('/api/v0/node/'.$ip.'/info.json');
	}

	public function pubkeyredirect($public_key) {

		/**
		 * Find node ip by public key.
		 *
		 * TODO: Memcached lookups.
		 *
		 * @return object
		 **/
		$node = Node::wherePublicKey($public_key.'.k')->first();

		return redirect('/node/'.$node->addr);

	}

	public function comments($ip) {

		$node = Node::where('privacy_level', '>', 0)->whereAddr($ip)->firstOrFail();
		$comments = \App\Comment::
				where('target', '=', $ip)
				->where('type', '=', 'App\Node')
				->orderBy('id', 'DESC')
				->paginate(6);

		if ( Request::ajax() ) {
			return response()->json($comments);

		}

		return view('node.comments', [
			'n' => $node,
			'comments' => $comments
			]);
	}

	public function autocompleteJson(Request $request) {

		$query = Request::input('query');
		$nodes = Node::where('addr', 'like', '%'.$query.'%')->lists('addr');
		$resp = [];
		foreach ( $nodes as $n ) {
			$node = [
			'value' => $n,
			'data' => $n
			];
			array_push($resp, $node);
		}
		$res['suggestions'] = $resp;

		return response()->json($res);

	}
	public function activityRss2($ip) {
		// TODO: Fixme
		$node = Node::where('privacy_level', '>', 0)->whereAddr($ip)->firstOrFail();
		$activity = (new \Activity())->where('actor_user_id', '=', $ip)->orderBy('id', 'DESC')->take(10)->get();
		$feed = new Feed();
		$channel = new Channel();
		$channel
			->title("{$ip}'s Activity Feed")
			->description("{$ip} doesn't have a description yet!")
			->url('http://hub.hyperboria.net')
			->language('en-US')
			->copyright('Copyright 2015, '.$ip)
			->pubDate(strtotime('Tue, 21 Aug 2012 19:50:37 +0900'))
			->lastBuildDate(strtotime('Tue, 21 Aug 2012 19:50:37 +0900'))
			->ttl(60)
			->appendTo($feed);
		foreach ($activity as $act) {
		$item = new Item();
		$item
			->title( e($act['description']) )
			->description( strip_tags($act['details']) )
			->url( 'http://hub.hyperboria.net/node/'.$ip.'/activity/'.e($act['id']) )
			->pubDate( strtotime($act['created_at']) )
			->guid( sha1($ip.$act['id']).$act['id'], false)
			->appendTo($channel);
		}

		return response($feed)->header('Content-Type', 'application/rss+xml');
	}
	/**
	 * Show the nodes followers.
	 *
	 * @return Response
	 */
	public function followers($ip) {

		$node = Node::where('privacy_level', '>', 0)->whereAddr($ip)->firstOrFail();
		$followers = \App\Follower::
		where('target', '=', $ip)
		->where('target_type', '=', 'App\Node')
		->paginate(100)
		->lists('follower_addr');

		return view('node.followers', ['n' => $node, 'f' => $followers]);
	}

	/**
	 * Show the nodes followers.
	 *
	 * @return Response
	 */
	public function followersJson($ip) {

		$followers = \App\Follower::
		where('target', '=', $ip)
		->where('target_type', '=', 'App\Node')
		->paginate(100)
		->lists('follower_addr');

		return response()->json($followers);
	}

	/**
	 * Show the users the node follows.
	 *
	 * @return Response
	 */
	public function follows($ip) {

		$node = Node::where('privacy_level', '>', 0)->whereAddr($ip)->firstOrFail();
		$follows = \App\Follower::
		where('follower_addr', '=', $ip)
		->where('target_type', '=', 'App\Node')
		->paginate(100)
		->lists('target');

		return view('node.follows', ['n' => $node, 'f' => $follows]);
	}

	/**
	 * Show the users the node follows.
	 *
	 * @return json Response
	 */
	public function followsJson($id) {

		$follows = \App\Follower::
		where('follower_addr', '=', $id)
		->where('target_type', '=', 'App\Node')
		->paginate(100)
		->lists('target');

		return response()->json($follows);
	}

	public function followed($ip) {
		$followed = (bool) \App\Follower::
		where('target', '=', $ip)
		->where('target_type', '=', 'App\Node')
		->where('follower_addr' ,'=', Request::ip())
		->first();
		//$followed = ($followed != null)?: false;
		return response()->json($followed);

	}

	/**
	 * Perform a new node follow action
	 *
	 * @return Response
	 */
	public function follow($id) {

		$author_ip = Request::ip();
		if($id == $author_ip) {
			// You  can't follow yourself!
			return false;
		}
		/**
		 * Check if user is already following that node.
		 *
		 * @return boolean
		 **/
		$state = \App\Follower::
		where('target', '=', $id)
		->where('follower_addr', '=', $author_ip)
		->first();
		if($state == null) {
			\Activity::log([
			    'actor_user_id'   => $author_ip,
			    'action_id'   => $id,
			    'action_user_id'   => $id,
			    'action_type' => 'Node@Follow',
			    'action'      => 'Follow',
			    'description' => 'Followed a node',
			    'details'     => 'Followed <a href="/node/'.$id. '">'.$id.'</a>',
			]);
			return (new \App\Follower())->create([
				'target' => $id,
				'target_type' => 'App\Node',
				'follower_addr' => $author_ip
				]);
		}
		else {
			return false;
		}
	}

	/**
	 * Perform an unsubscribe action.
	 *
	 * @return Response
	 */
	public function unfollow($id) {
		$author_ip = Request::ip();
		if($id === $author_ip) {
			return false;
		}
		$state = \App\Follower::
		where('target', '=', $id)
		->where('follower_addr', '=', $author_ip)
		->firstOrFail();
		if($state !== null OR $state !== false) {

			\Activity::log([
			    'actor_user_id'   => $author_ip,
			    'action_id'   => $id,
			    'action_user_id'   => $id,
			    'action_type' => 'Node@Unfollow',
			    'action'      => 'Unfollow',
			    'description' => 'Unfollowed a node',
			    'details'     => 'Unfollowed <a href="/node/'.$id. '">'.$id.'</a>',
			]);
			return (new \App\Follower())->destroy($state->id);
		}
		else {
			return false;
		}
	}

	public function commentAdd($ip) {
		$input = \Input::all();
		if(isset($input['caid']) && $input['caid'] == sha1(csrf_token().Request::ip())) {
			$comment = new \App\Comment;
			$comment->target = $ip;
			$comment->type = 'App\Node';
			$comment->author_addr = Request::ip();
			$comment->body = $input['body'];
			$comment->save();
			\Activity::log([
			    'actor_user_id'   => Request::ip(),
			    'action_id'   => $ip,
			    'action_user_id'   => $id,
			    'action_type' => 'Node@Comment',
			    'action'      => 'New Comment',
			    'description' => 'Added a comment to a node.',
			    'details'     => 'Commented on <a href="/node/'.$ip. '">'.$ip.'</a>',
			]);
			return redirect('/node/'.$ip);
		}
		else {
		return view('errors.403');
		}
	}


	public function statusUpdate(Request $req) {
		$v = \Validator::make(Request::all(), [
		        'body' => 'required|min:5|max:140',
		]);

		if ($v->fails()) {
		    return redirect()->back()->withErrors($v->errors());
		}
		$input = Request::all();
		if(isset($input['caid']) ) {
			$ip = Request::ip();
			$comment = new \App\Comment;
			$comment->target = $ip;
			$comment->type = 'App\Node';
			$comment->author_addr = Req::ip();
			$comment->body = e($input['body']);
			$comment->save();
			\Activity::log([
			    'actor_user_id'   => Req::ip(),
			    'actor_type' 	=> 'Node',
			    'action_id'   => $ip,
			    'action_user_id'   => $ip,
			    'action_type' => 'Node@Status',
			    'action'      => 'Status Update',
			    'description' => $comment->body,
			    'details'     => 'Status Update',
			]);
			return redirect('/node/'.$ip);
		}
		else {
		return view('errors.403');
		}
	}
	public function peerRequestView($ip, Request $request) {

		$requester = $request->ip();

		$node = Node::whereAddr($ip)->get();
		$hash = hash('sha256', $ip.$node[0]['public_key']);
		$pr = Peer::whereHash($hash)->first();
		$exists = ($pr == null) ? false : true;

		if ($exists === true) {
			return response()->json([
				'error' 			=> true,
				'error_msg'		=> 'You are already peered with this node.',
				'error_code'	=> 403,
				'help_url'		=> '/docs/node/peer/duplicate#cannot+add',

				],
			200,
			[],
			JSON_PRETTY_PRINT);
		}
		return view('node.peer-request', ['ip' => $ip]);

	}
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create() {

		$ip = Request::ip();

		if(\Req::ip($ip) == false) {
			return redirect()->back()->withErrors('Invalid cjdns ipv6 detected');
		}
		/**
		 * Check if node exists.
		 *
		 * @return object
		 **/
		$node = Node::whereAddr($ip)->first();
		if(!$node) {
			/**
			 * Create new node object and pass off to edit view.
			 *
			 * @return view
			 **/
			$node = new Node;

			// FIXME: Add to pubKeyDiscoveryQueue
			$node->public_key = 'temp'.rand(0,99).'-'.$ip;

			$node->addr = $ip;
			$node->save();

			return view('node.edit', [
				'n' => $node
				]);
		}
		else {
			$err = ['Error: Node already exists.'];
			return redirect()->back()->withErrors($err);
		}

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($ip, Request $request)
	{
		if($ip !== Request::ip()) return redirect('/');
		$node = Node::whereAddr(Request::ip())->firstOrFail();

		return view('node.edit',[
			'n' => $node
			]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
