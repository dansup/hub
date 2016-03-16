<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Hub\Node\Node;

class SiteController extends Controller
{

  public function index()
  {
    $nodes = Node::orderBy('updated_at', 'DESC')->take(8)->get();
    return view('welcome', compact('nodes'));
  }

  public function browseNodes()
  {
    $nodes = Node::orderBy('updated_at', 'DESC')->paginate(10);
    return view('node.browse', compact('nodes')); 
  }
}
