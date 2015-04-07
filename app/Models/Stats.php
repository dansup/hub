<?php
/* 
* Stats Class
*/
namespace App\Models;

use PDO;

class Stats {

    protected $db;

    function __construct()
    {
        $this->db = new \App\Models\Database();
    }   
    public function getTotalNodes() {
        $db = $this->db;
        $stmt = $db->prepare('SELECT count(addr) from nodes;');
        $stmt->execute();
        $total = $stmt->fetch(PDO::FETCH_COLUMN);
        return $total;
    }
    public function getAverageVersion() {
        $db = $this->db;
        $stmt = $db->prepare('SELECT avg(version) from nodes;');
        $stmt->execute();
        $total = $stmt->fetch(PDO::FETCH_COLUMN);
        return round($total);
    }
    public function getAverageLatency() {
        $db = $this->db;
        $stmt = $db->prepare('SELECT avg(latency) from nodes;');
        $stmt->execute();
        $total = $stmt->fetch(PDO::FETCH_COLUMN);
        return round($total);
    }
    public function getAveragePeers() {
        $db = $this->db;
        $stmt = $db->prepare('SELECT avg(count(peer_pubkey)) from peers where DISTINCT(origin_ip);');
        $stmt->execute();
        $total = $stmt->fetch(PDO::FETCH_COLUMN);
        return round($total);
    }
}
