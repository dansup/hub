<?php
  /* 
    *   Hub
    *   Nodeinfo Controller 
    *
    */
namespace App\Controllers;

class Nodeinfo {

    public function getNodeInfo($ip) {
        return (new \App\Models\Nodeinfo())->fetch($ip);
        
    }
}