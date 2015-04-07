<?php
/**
*  Meshmap Class
*/
namespace App\Models;

use \App\Config\App as Config;
use PDO;
use \App\Utils\Pagination as Pagination;

class Meshmap  {

    protected $db;

    function __construct()
    {
        $this->db = new \App\Models\Database();
    } 
    public function getPoints() {
/*        $dbh = $this->db->prepare('
            SELECT lat, lng, addr from nodes
            WHERE map_privacy = 1;
            ');
        $dbh->execute();
        $resp = $dbh->fetchAll(PDO::FETCH_ASSOC);
        //$geojson = [
/*        'type' => 'FeatureCollection', 
        'features' => []
        ];
        $points = [];
        foreach($resp as $p) {
            $point = [
            "type" => "Feature",
            "geometry" => [
            "type" => "Point",
            "coordinates" => [floatval($p['lng']), floatval($p['lat'])]
            ],
            "properties"=> [
            "title" => $p['addr'],
            "description" => $p['addr'],
            "url" => "/node/{$p['addr']}",
            "marker-color" => "#F5AB35",
            "marker-size" => "large",
            "marker-symbol" => "circle"]
            ];
            array_push($points, $point);
        }
        return json_encode($points, JSON_PRETTY_PRINT|JSON_NUMERIC_CHECK);*/
        $q = $this->db->prepare(
            "SELECT nodestatus, hostname, addr, addr_verified, lat, lng, node_type, ownername, first_seen 
            FROM nodes 
            WHERE map_privacy = 1;"); 
    //$q->bindParam(1, $uid);
        $q->execute();
        $json = $q->fetchAll(PDO::FETCH_ASSOC); 
        foreach($json as $result)
        {
            $hostname = $result['hostname'];
            $addr = $result['addr'];
            $addr_verified = $result['addr_verified'];
            if($addr_verified==0)
            {
                $addr_v = false;
            }
            else
                $addr_v = true;
            $type = null;
            $lat = $result['lat'];
            $lng = $result['lng'];
            $status = $result['nodestatus'];
            $rdate = $result['first_seen'];
            $owner = $result['ownername'];
            if($status===0)
            {
                $status = "Active";
            }
            $jsonf[] = array('hostname' => $hostname, 'cjdns_ip' => $addr, 'cjdns_ip_verified' => $addr_v, 'owner' => $owner, 'lat' => $lat, 'lng' => $lng, 'status' => $status, 'node_type' => $type, 'registration_date' => $rdate);
        }

        return json_encode($jsonf, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
    }

}