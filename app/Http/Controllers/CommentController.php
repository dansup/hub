<?php namespace App\Http\Controllers;

use App\Http\Reqs;
use App\Http\Controllers\Controller;
use App\Hub\Req;
use App\Node;

class CommentController extends Controller {

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
	public function store(Req $Req)
	{
		$v = \Validator::make(Req::all(), [
		        'caid' => 'required|min:39|max:42',
		        'ct' => 'required',
		        'cid' => 'required|min:10|max:60',
		        'body' => 'required|min:5|max:140',
		]);

		if ($v->fails())
		{
		    return redirect()->back()->withErrors($v->errors());
		}
		$input = Req::all();
		if(isset($input['caid']) ) {
			$ip = Req::ip($input['cid']);
			$comment = new \App\Comment;
			$comment->target = $ip;
			$comment->type = 'App\Node';
			$comment->author_addr = Req::ip();
			$comment->body = e($input['body']);
			$comment->save();
			\Activity::log([
			    'actor_user_id'   => Req::ip(),
			    'actor_type' 	=> 'Node',
			    'action_id'   => $ip,
			    'action_user_id'   => $ip,
			    'action_type' => 'Node@Comment',
			    'action'      => 'New Comment',
			    'description' => 'New comment',
			    'details'     => 'Commented on <a href="/nodes/'.$ip. '">'.$ip.'</a>',
			]);
			return redirect('/nodes/'.$ip);
		}
		else {
		return view('errors.403');
		}
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
