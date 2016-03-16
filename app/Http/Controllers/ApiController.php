<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth, Cache, App\User, App\Hub\Node\Node, App\Comment;
class ApiController extends Controller
{
    public function createComment(Request $request)
    {
      $this->middleware('auth');
      $this->validate($request, [
        'commentable_id' => 'integer|required',
        'commentable_type' => 'required|min:4|max:5',
        'body'          => 'required|max:250',
        ]);
      $user = Auth::user();
      $node = Node::findOrFail($request->input('commentable_id'));
      $comment = new Comment;
      $comment->commentable_id = $node->id;
      $comment->commentable_type = 'App\Hub\Node\Node';
      $comment->user_id = Auth::id();
      $comment->body = e($request->input('body'));
      $comment->save();

      return redirect()->back();
    }

    public function v1NodePeers(Request $request, $ip)
    {
      $ip = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
      $res = Cache::remember('node.'.$ip.'.peers.json', 10080, function() use($ip) {
        $res = [];
        $node = Node::with('peers')->whereAddr($ip)->firstOrFail();
        $cc = $node->peers()->selectRaw('DISTINCT(peer_key)')->get()->count();
        if($cc > 0) {
          foreach($node->peers()->selectRaw('DISTINCT(peer_key)')->get() as $peer) {
            $child = Node::wherePublicKey($peer->peer_key)->where('addr', '!=', $ip)->first();
            if($child != null) {
              $res[] = [
              'ip'  => $child->addr,
              'cjdns_version' => $child->version,
              'publicKey' => $child->public_key,
              'owner' => $child->ownername,
              'peer_count' => ($child->peers()->selectRaw('DISTINCT(peer_key)')->get()->count() - 1),
              'last_seen' => $child->updated_at
              ];
            }
          }
          $node->peerCount = $cc;
          $node->peercount_updated_at = date('c');
          $node->update();
        }
        return $res;
      });
      return response()->json($res, 200, [], JSON_PRETTY_PRINT);
    }
    public function v1NodePeerGraph(Request $request, $ip)
    {
      $ip = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
      $res = Cache::remember('node.'.$ip.'.peergraph.json', 10080, function() use($ip) {
        $res = [];
        $nodes = [];
        $edges = [];
        $node = Node::with('peers')->whereAddr($ip)->firstOrFail();
        $cc = $node->peers()->selectRaw('DISTINCT(peer_key)')->get()->count();
        if($cc > 0) {
          $i = 0;
          foreach($node->peers()->selectRaw('DISTINCT(peer_key)')->get() as $peer) {
            $child = Node::wherePublicKey($peer->peer_key)->first();
            if($child != null) {
              if(empty($child->addr) == true) {
                $label = substr($child->public_key, 0, 6);
              } else {
              $ipf = explode(':', $child->addr);
              $label = ($child->ownername == null) ? $ipf[0].':'.$ipf[7] : $child->ownername;
              }
              $value = ($child->peerCount == 0 ? 0 : $child->peerCount - 1);
              $nodes[] = [
              'id'  => $i,
              'value' => $value,
              'label' => $label
              ];
                if($child->addr !== $ip) {
                $edges[] = [
                'from' => 0,
                'to' => $i,
                'value' => $value,
                'title' =>  $child->addr.' has '.$value.' total peers'
                ];
              }
            }
            $i++;
          }
          $node->peerCount = $cc;
          $node->update();
        $res = [
        'nodes' => $nodes,
        'edges' => $edges
        ];
        }
        return $res;
      });
      return response()->json($res, 200, [], JSON_PRETTY_PRINT);
    }
}
