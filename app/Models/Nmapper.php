<?php
namespace App\Models;

use PDO;
use \Nmap\Host;
use \Nmap\Nmap;
use \Nmap\Port;

class Nmapper {

        protected $db;

        function __construct() {
            $this->db = new \App\Models\Database();
        }

        public function newScan($ip)
        {
            $nmap = new Nmap();
            $resp = $nmap
            ->enableIPv6()
            ->enableServiceInfo()
            ->scan([ $ip ]);
            $resp = json_decode(json_encode($resp), true);

            if($resp[0]['state'] == "up") {
                $ports = [];
                foreach ($resp[0]['ports'] as $key) {
                    $data[] = [
                    'port' => $key['number'],
                    'protocol' => $key['protocol'],
                    'state' => $key['state'],
                    'service' => $key['service']['name']
                    ];
                    array_push($ports, $key['number']);
                }
                $dbh = $this->db->prepare('
                    INSERT into nmap
                    (ts, addr, ports, data)
                    VALUES(:ts, :addr, :ports, :data);');
                $dbh->bindParam(':ts', date('c'));
                $dbh->bindParam(':addr', $ip);
                $dbh->bindParam(':ports', json_encode($ports) );
                $dbh->bindParam(':data', json_encode($resp));
                if(!$dbh->execute()) {
                    return var_dump($dbh->errorInfo());
                }
                return true;
        }
        else {
            return false;
        }

}
}
