<?php namespace App\Http\Controllers;

use Input;
use Redis;
use Request;
use Response;

class PeerstatsController extends Controller {

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(Request $request)
  {

        /* FIXME: Input::ip() doesn't pad ipv6, which conflicts with the node model */
        $ip = Input::ip();


        $stats = Input::all();

        $res = Redis::set('node:stats:'.$ip, json_encode($stats));

        return response()->json([
          'res' => true,
          'data' => $stats
          ]);
  }

}
