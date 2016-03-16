<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth, Cache;

class UserController extends Controller
{
    public function getSessionHeartbeat(Request $request)
    {
      $heartbeat = Cache::remember('user.session.id.'.Auth::id(), 3660, function() {
          $user = Auth::user();
          $res = [
          'user_role' => 'member',
          'aip' => $user->ipv6,
          'user_settings' => []
          ];
          return $res;
      });
      return response()->json($heartbeat, 200, [], JSON_PRETTY_PRINT);
    }


}
