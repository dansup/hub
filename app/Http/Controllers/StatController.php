<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Cache, App\Peer, App\Hub\Node\Node, App\Hub\Service;

class StatController extends Controller
{
    public function v1GeneralStats(Request $request)
    {
      $data = Cache::remember('site.stats.general.json', 720, function() {
        $nodeCount = Node::count();
        $nodeYear = Node::whereRaw('updated_at >= date_sub(now(), INTERVAL 1 year)')->count();
        $nodeMonth = Node::whereRaw('updated_at >= date_sub(now(), INTERVAL 1 month)')->count();
        $nodeWeek = Node::whereRaw('updated_at >= date_sub(now(), INTERVAL 1 week)')->count();
        $nodeDay = Node::whereRaw('updated_at >= date_sub(now(), INTERVAL 1 day)')->count();
        $nodeHour = Node::whereRaw('updated_at >= date_sub(now(), INTERVAL 1 hour)')->count();
        $peerCount = Peer::count();
        $peerDistinctNodes = Peer::count()
        $nodeAvgVersion = round(Node::avg('version'));
        $nodeAvgPeers = round(Node::avg('peerCount'));
        $nodeAvgService = round(Node::avg('serviceCount'));

        $res = [
          'total_known_nodes' => $nodeCount,
          'active_within_year' => $nodeYear,
          'active_within_month' => $nodeMonth,
          'active_within_week' => $nodeWeek,
          'active_within_day' => $nodeDay,
          'active_within_hour' => $nodeHour,
          'average_cjdns_version' => $nodeAvgVersion,
          'average_cjdns_peers' => $nodeAvgPeers,
          'average_cjdns_services' => $nodeAvgService
        ];

        $data = [
        'notice' => 'We are finalizing the v0 endpoints, and as a result the schema may change. Please be advised we do not recommend using v0 for anything important as the schema may change at any time without notice.',
        'endpoint_cache_duration' => '12 hours',
        'generated' => date('c'),
        'data_sha1' => hash('sha1', json_encode($res)),
        'data_count' => count($res),
        'data'  => $res
        ];
        return $data;
      });
      return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
}
