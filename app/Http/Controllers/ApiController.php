<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Hub\Req as Request;
use App\Http\Requests\ApiUpdateNodeRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Node;
use App\Peer;
use App\Peerstats;

class ApiController extends Controller {


	public function getNodePeers($ip) {

		$Peerstats = new Peerstats;
		$recentPeers = $Peerstats->Peerstats_getCollection($ip);

		$unset = [ '_id', 'node' ];
		foreach ($unset as $ignored => $pair) {
			unset($pair);
		}

		unset($recentPeers['_id']);
		unset($recentPeers['node']);
		return response()->json($recentPeers);

		/********/
		// $peers = Node::whereAddr($ip)->firstOrFail()->peers->lists('peer_key');
		// return response()->json($peers);
		/********/

	}

	public function nodePeersChart($ip = null, $public_key = false) {

		if($ip == null) return false;
		$days = 307;
		$range = \Carbon\Carbon::now()->subDays($days);

		$chart = \DB::table('peers')
				->whereOriginIp($ip)
				->where('created_at', '>=', $range)
				->distinct('peer_key','origin_key')
				->groupBy('x')
				->orderBy('x', 'ASC')
				->get([
				  \DB::raw('Date(created_at) as x'),
				  \DB::raw('COUNT(distinct(peer_key)) as y')
		]);
		//$chart = Peer::select('nodes.addr as y', 'nodes.created_at as x')
		//->leftJoin('nodes', 'peers.peer_key', '=', 'nodes.public_key')
		//->where('nodes.addr', '!=', 'NULL')
		//->whereOriginIp($ip)->distinct('peer_key')->get();

		// TODO: Fix self peering bug
		//if($chart[0]['addr'] == $ip) unset($chart[null]);

		return response()->json($chart, 200, [], JSON_PRETTY_PRINT);
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
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
	public function edit($id)
	{
		//
	}
	public function postNodePeerRequest(Request $request, $ip) {

		return response()->json($request::all(), 200, [], JSON_PRETTY_PRINT);
	}
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function updateNode(ApiUpdateNodeRequest $request)	{

		$input = $request::all();
		$ip = Request::ip();
		if ( !\hash_equals(sha1($ip.$input['_token']), $input['web_token'] ) ) {
			return response()->json([
				'response' 		=> 403,
		        'status' 		=> 'forbidden',
				'error' 		=> true,
		        'error_msg'		=>'Endpoint is exclusive to the website, please use the public endpoint.',
		        'use_instead' 	=> 'http://dev.hub.hyperboria.net/api/v0/node/'.$ip.'/update.json',
				],
				403,
				[],
				JSON_PRETTY_PRINT);
		}
		$node = Node::whereAddr($ip)->firstOrFail();
		$act_desc = [];
		unset($input['_token'], $input['web_token']);
		foreach ($input as $k => $v) {
			if( !empty($v) ) {
				$v = trim($v);
				$act_desc[] = $k;
				switch ($k) {
					case 'hostname':
						$node->hostname = $v;
						break;

					case 'ownername':
						$node->ownername = $v;
						break;
					case 'bio':
						$node->bio = $v;
						break;
					case 'city':
						$node->city = $v;
						break;
					case 'province':
						$node->province = $v;
						break;
					case 'country':
						$node->country = $v;
						break;
					case 'lat':
						$node->lat = floatval($v);
						break;
					case 'lng':
						$node->lng = floatval($v);
						break;

					default:
						break;
				}
			}
		}
		$node->update();
		\Activity::log([
		    'actor_user_id'   => $ip,
		    'action_id'   => $ip,
		    'action_user_id'   => \Auth::id(),
		    'action_type' => 'Node@Update',
		    'action'      => 'Update Info',
		    'description' => 'Updated node info.',
		    'source'	  => 'Web',
		    'details'     => 'Updated '.implode(',', $act_desc).' field(s)',
		]);
		return redirect('/node/'.$ip);


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
