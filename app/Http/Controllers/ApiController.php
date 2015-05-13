<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\ApiUpdateNodeRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Node;

class ApiController extends Controller {


	public function getNodePeers($ip) {
		
		$peers = Node::findOrFail($ip)->peers->lists('peer_key');

		return response()->json($peers);

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

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function updateNode(ApiUpdateNodeRequest $request)	{

		$input = $request->all();
		$ip = Request::getClientIp();
		unset($input['_token'], $input['addr']);
		$node = Node::where('addr', $ip)->first();
		foreach ($input as $k => $v) {
			if( !empty($v) ) {
				$v = trim($v);
				switch ($k) {
					case 'hostname':
						$node->hostname = $v;
						break;

					case 'ownername':
						$node->ownername = $v;
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
						$node->lat = $v;
						break;
					case 'lng':
						$node->lng = $v;
						break;
					
					default:
						break;
				}
			}
		}
		$node->save();
		\Activity::log([
		    'actor_user_id'   => $ip,
		    'action_id'   => $ip,
		    'action_user_id'   => \Auth::id(),
		    'action_type' => 'Node@Update',
		    'action'      => 'Update Info',
		    'description' => 'Updated node info.',
		    'source'	  => 'Web',
		    'details'     => 'Updated node info.',
		]);
		return redirect('/nodes/'.$ip);


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
