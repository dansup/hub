<?php

namespace App\Http\Controllers;


use App\PeerRequest;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Hub\Req as Request;

class PeerRequestController extends Controller
{
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
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request, $ip)
    {

        $reason = $request->input('reason');
        $rtype = $request->input('rtype');
        $creds = $request->input('creds');
        $body = $request->input('body');

        $exists = PeerRequest::where('target_ip', '=', $ip)->where('request_ip', '=', Request::ip())->first();
        $pr = new PeerRequest;

        return response()->json([$exists,$request->all()], 200, [], JSON_PRETTY_PRINT);
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
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
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
