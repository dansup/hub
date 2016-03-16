<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Hub\Service;

class ServiceController extends Controller
{
    public function home(Request $request)
    {
      $services = Service::whereIsUnlisted(false)
      ->whereIsInactive(false)
      ->whereIsNsfw(false)
      ->orderBy('updated_at', 'desc')
      ->paginate(10);
      return view('service.home', compact('services'));
    }

    public function addNew(Request $request)
    {
      return view('service.create');
    }

    public function storeNew(Request $request)
    {
      $this->validate($request, [
        'service_type' => 'required',
        'name' => 'required|min:3|max:25',
        'url' => 'required|min:3|max:255',
        'description' => 'required|min:3|max:255',
        ]);
      $user = Auth::user();
      $count = Service::whereIpv6(request()->ip())->count();
      if($count > 30) {
        return response()->json([
          'error' => 'Exceeds maximum allowed services per ipv6.'
          ], 403);
      }
      switch ($request->input('service_type')) {
        case 'website':
          $serviceType = 'website';
          break;
        case 'irc':
          $serviceType = 'irc';
          break;
        case 'bitcoin':
          $serviceType = 'bitcoin';
          break;
        
        default:
          $serviceType = 'unknown';
          break;
      }
      $name = e($request->input('name'));
      $desc = e($request->input('description'));
      $slug = str_slug($name, '-');
      $user->serviceCount = $user->serviceCount++;
      $user->update();
      $service = new Service;
      $service->url = filter_var($request->input('url'), FILTER_VALIDATE_URL);
      $service->service_type = $serviceType;
      $service->name = $name;
      $service->slug = $slug;
      $service->ipv6 = $request->ip();
      $service->description = $desc;
      $service->save();
      return redirect()->intended('/service');
    }
}
