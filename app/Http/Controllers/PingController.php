<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Hub\Cjdns\Api;
use App\Ping;

use Illuminate\Http\Request;

class PingController extends Controller {


	public function apiPing($ip) {

		$capi = new Api;
		$resp = $capi->call(
			'RouterModule_pingNode',[
			'path' => $ip
		]);
		if(isset($resp['result']) && $resp['result'] == 'pong') {

			$ping = new Ping;

            $frag = explode('.', $resp['addr']);

			$ping->addr = $ip;
			$ping->latency = $resp['ms'];
			$ping->protocol = substr($frag[0], 1);
			$ping->request_ip = env('CJDNS_IP');
			//$ping->public_key = $frag[5].'k';
			//$ping->route_label = "{$frag[1]}.{$frag[2]}.{$frag[3]}.{$frag[4]}";
			$ping->save();
			return true;
		}
		else return false;
	}

}
