<?php
/* 
  * API Class
  */
namespace App\Models;

use PDO;
use \App\Models\Node;

class Api
{

    protected $db;

    function __construct()
    {
        $this->db = new \App\Models\Database();
    } 


}