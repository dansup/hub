<?php
namespace App\Controllers;


class Api {

    public function getV0NodeInfo($addr) {
            $resp = (new \App\Controllers\Nodeinfo())->getNodeInfo($addr);
            return $resp;
    }

}