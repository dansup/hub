<?php

namespace App\Controllers;

use App\Models\Memcached;

class ApiController  extends Memcached {


    function __construct()
    {
        parent::__construct();
    }

    public function getV0NodeInfo($addr, $cache_enabled) {
            if($addr == false) return false;
            $cache = $this->m->get("h-node-info-api-v0-{$addr}");
            if($cache && $cache_enabled) return $cache;
            $resp = (new \App\Controllers\NodeinfoController())->getNodeInfo($addr);
            return $resp;
    }

}