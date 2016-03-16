<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth, App\Hub\Node\Node, App\Hub\Service, App\User, App\Comment;

class NodeController extends Controller
{
    public function node($ip)
    {
      $ip = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
      return Node::whereAddr($ip)->firstOrFail();
    }
    public function show(Request $request, $ip)
    {
      $node = $this->node($ip);
      $comments = $node->comments()->orderBy('id','desc')->paginate(6);
      return view('node.show', compact('node', 'comments'));
    }

    public function showServices(Request $request, $ip)
    {
      $node = $this->node($ip);
      $services = Service::whereIpv6($ip)->orderBy('id','desc')->paginate(6);
      return view('node.services', compact('node', 'services'));
    }
    public function showPeerGraph(Request $request, $ip)
    {
      $node = $this->node($ip);
      return view('node.peergraph', compact('node'));
    }

    public function showWIP(Request $request, $ip)
    {
      $node = $this->node($ip);
      $comments = $node->comments()->orderBy('id','desc')->paginate(6);
      return view('node.wip', compact('node'));
    }

    public function addComment(Request $request, $id)
    {
      $this->middleware('auth');
      $this->validate($request, [
        'body'          => 'required|max:250',
        ]);
      $user = Auth::user();
      $node = $this->node($id);
      $comment = new Comment;
      $comment->commentable_id = $node->id;
      $comment->commentable_type = 'App\Hub\Node\Node';
      $comment->user_id = Auth::id();
      $comment->body = e($request->input('body'));
      $comment->save();

      return redirect()->back();
    }
    public function showPeers(Request $request,$ip)
    {
      $node = $this->node($ip);
      return view('node.peers', compact('node'));
    }
}
