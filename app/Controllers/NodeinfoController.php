<?php
  /* 
    *   Hub
    *   Nodeinfo Controller 
    *
    */
namespace App\Controllers;

use App\Models\Memcached;

class NodeinfoController extends Memcached {


    function __construct()
    {
        parent::__construct();
    }

    public function getNodeInfo($ip) {
        $data = (new \App\Models\Nodeinfo())->fetch($ip);
        if($data == false OR $ip == false) return false;
        $resp = $data['data'][0];
        $this->m->set("h-node-info-api-v0-{$ip}", $resp, time() + 3600);
        return $resp;
        
    }
}