<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Peer;
use App\Node;

use Illuminate\Http\Request;

class MapController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('map.index');
	}


	public function sigma() {

		return view('map.sigma');
	}

	public function graphNodeJson() {
		$nodes = Node::all();
		$node = [];
		foreach ($nodes as $n) {
			
			$ip = $n['addr'];
			array_push($node, [
			'id' => $ip,
			'label' => substr($ip, 0,4).':'.substr($ip, 35, 39),
			'title' => '<a href="/nodes/'.$ip.'">'.$ip.'</a>',
			]);
		}
			return response()->json($node);
		
	}
	public function graphEdgeJson() {
		$nodes = Node::all();
		$data = Peer::all();
		$edge = [];
		$ei = 0;
		$pk = [];
		foreach($nodes as $n) {
			$pk[$n['public_key']] = $n['addr'];
		}
		foreach ($data as $d) {
			$ei++;
			$oid = (isset($d['origin_ip'])) ? $d['origin_ip'] : false;
			$tid = (isset($pk[$d['peer_key']])) ? $pk[$d['peer_key']] : false;
			$no = ($oid == $tid) ? true : false;
			if($no == false && $oid !== false && $tid !== false) {
				array_push($edge,[
				'id' => $ei,
				'from' => $oid,
				'to' => $tid,
				'title' => $oid.' => '.$tid,
				]);				
			}
		}
		return response()->json($edge);
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
