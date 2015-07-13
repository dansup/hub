<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Service;

class ServiceController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()	{
		$services = \App\Service::orderBy('updated_at', 'DESC')->paginate(10);
		return view('service.home', ['services' => $services]);
	}


	public function view($id, $name) {
		$service = \App\Service::find($id);
		return view('service.view', ['s' => $service, 'n' => $service->node]);
	}

	public function followers($id) {
		return true;
	}
	
	public function follows($id) {
		return true;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create() {
		return view('service.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)	{

    $this->validate($request, [
        'addr' => 'required',
        'name' => 'required|min:3|max:40',
        'port' => 'required|integer|min:1|max:65535',
        'url' => 'required',
        'bio' => 'min:5|max:140',
        'city' => 'min:3|max:50',
        'country' => 'min:3|max:50',
    ]);

    if(\Req::ip() !== $request->input('addr')) abort(403, 'Attempted address manipulation');
    $s = new Service;

    $s->addr = \Req::ip();
    $s->name = e($request->input('name'));
    $s->port = intval(e($request->input('port')));
    // FIXME: port udp/tcp detection
    $s->protocol = getservbyport($s->port, 'tcp');
    $s->url = filter_var($request->input('url'), FILTER_VALIDATE_URL);
    $s->bio = (empty($request->input('bio'))) ? null : e($request->input('bio'));
    $s->city = (empty($request->input('city'))) ? null : e($request->input('city'));
    $s->country = (empty($request->input('country'))) ? null : e($request->input('country'));
		$s->save();

		return redirect('/service/'. $s->id .'/'. str_slug($s->name, '-'));

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id) {
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id) {
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id) {
		//
	}

}
