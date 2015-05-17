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
		$data = Peer::orderBy('id', 'DESC')->take(1000)->distinct( ['origin_ip' => 'peer_key'] )->get();
		$edge = [];
		$ei = 0;
		$pk = [];
		foreach($nodes as $n) {
			$pk[$n['public_key']] = $n['addr'];
		}
		foreach ($data as $d) {
			$oid = (isset($d['origin_ip'])) ? $d['origin_ip'] : false;
			$tid = (isset($pk[$d['peer_key']])) ? $pk[$d['peer_key']] : false;
			$no = ($oid == $tid) ? true : false;
			if($no == false && $oid !== false ) {
				array_push($edge,[
				'id' => $ei,
				'from' => $oid,
				'to' => $tid,
				'hash' => sha1($oid.$tid),
				'title' => $oid.' => '.$tid,
				]);	
				$ei++;			
			}
		}
		return response()->json($edge, 200, [], JSON_PRETTY_PRINT);
	}


}
