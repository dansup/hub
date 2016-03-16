<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth, Uuid, App\Hub\Node\Node, App\Hub\AbuseReport;

class AbuseController extends Controller
{
    public function newNodeReport(Request $request, $ip)
    {
      $this->validate($request, [
        'body' => 'required|max:1000',
        'report_type' => 'required|integer|min:1|max:10',
        'title' => 'string|min:3|max:30',
        'severity' => 'required|integer|min:1|max:10'
        ]);
      $node = Node::whereAddr($ip)->firstOrFail();
      $reporter = Auth::user();
      switch ($request->input('report_type')) {
        case 1:
          $rt = 'verbal-abuse';
          $rl = 'Verbal abuse';
          break;
        case 2:
          $rt = 'directed-harassment';
          $rl = 'Harassment directed at a person';
          break;
        case 3:
          $rt = 'dos';
          $rl = 'Denial of Service attack';
          break;
        case 4:
          $rt = 'hacking-attempt';
          $rl = 'Hacking attempts';
          break;
        case 5:
          $rt = 'hacking';
          $rl = 'Successful hacking attacks';
          break;
        
        default:
          $rt = 'abuse';
          $rl = 'abuse';
          break;
      }
      $report = new AbuseReport;
      $report->content_id = $node->id;
      $report->content_type = 'App\Hub\Node\Node';
      $report->reporter_ip = request()->ip();
      $report->user_id = $reporter->id;
      $report->report_type = $rt;
      $report->report_label = $rl;
    }
}
