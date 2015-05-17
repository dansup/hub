<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Hub\Cjdns\Api;
use App\Node;
use App\Ping;
use App\Peer;

use Illuminate\Http\Request;

class PingController extends Controller {


	public function apiPing($ip) {

		$capi = new Api;
		$resp = $capi->call('RouterModule_pingNode',['path' => $ip,'timeout' => 3000]);
		if(isset($resp['error']) && $resp['error'] === 'not_found') return false;
		if(isset($resp['result']) && $resp['result'] == 'pong') {

			$ping = new Ping;
            $frag = explode('.', $resp['addr']);
			$protocol = (int) substr($frag[0], 1);
			$latency = (int) $resp['ms'];
			$ping->addr = $ip;
			$ping->latency = $latency;
			$ping->protocol = $protocol;
			$ping->request_ip = env('CJDNS_IP');
			//$ping->public_key = $frag[5].'k';
			//$ping->route_label = "{$frag[1]}.{$frag[2]}.{$frag[3]}.{$frag[4]}";
			$ping->save();

			$peers = $capi->call(
				'RouterModule_getPeers', [
				'path' => $ip
				]);
			
			$peers = ($peers['error'] !== 'not_found' && isset($peers['peers'])) ? $peers : false;
			if($peers !== false) {
				foreach ($peers['peers'] as $data) {
					$frag = explode('.', $data);
					
					$public_key = $frag[5].".k";
					$version = substr($frag[0], 1);

					$peer = new Peer;

					$peer->origin_ip = $ip;
					$peer->peer_key = $public_key;
					$peer->protocol = $protocol;
					$peer->monitor_ip = env('CJDNS_IP');

					$peer->save();

				}
			}

			// Update node latency and version
			$node = Node::whereAddr($ip)->firstOrFail();
			$node->version = $protocol;
			$node->latency = $latency;
			$node->touch();
			$node->save();

			return true;
		}
		else return false;
	}

}
