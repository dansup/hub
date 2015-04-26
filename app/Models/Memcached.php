<?php

namespace App\Models;

class Memcached {

    function __construct() {
        $this->m = new \Memcached();
        $this->m->addServer('localhost', 11211);
    }
}