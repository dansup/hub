<?php 
/* File:    core.inc.php - v 1.0
* Hub - The Hyperboria Analytics Machine
* Created: June 15/2014
* Last Edited: June 16/2014
* classes: cjdnsApi ( api specific actions ), Node (node specific actions ), Router ( app models/views/controllers )
* dependencies: app config, pagination library, cjdns php api (thanks finn)
*
* todo:  cleanup!
*/
require_once('config.inc.php');
require_once('page.inc.php');
require_once("capi/b.inc.php");
require_once("capi/c.inc.php");
require_once("capi/vendor/autoload.php");
use Nmap\Host;
use Nmap\Nmap;
use Nmap\Port;

class cjdnsApi 
{
    private function DB()
    {

        try
        {
            return new PDO(dbtype.':dbname='.dbname.';host='.dbhost, dbuser, dbpass);
        }
        catch (PDOException $e)
        {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }
    public function availableFunctions()
    {
        $funcs = [];
        $page = 0;
        while(True) {
            $capi = new Cjdns(CJDNS_API_KEY);
            $result = $capi->call("Admin_availableFunctions", array("page"=>$page));
            foreach($result['availableFunctions'] as $function => $description) {
                $funcs[$function] = $description;
            }
            if(isset($result['more'])) {
                $page++;
            } else {
                break;
            }
        }
        return $funcs;
    }
    public function latency()
    {
        return $this->latency;
    }
    public function RouterModule_pingNode($path)
    {
        $capi = new Cjdns(CJDNS_API_KEY);
        $ping_r[] = $capi->call("RouterModule_pingNode",array("path"=>$path));
        return $ping_r;
    }
    public function result()
    {
        return $this->result;
    }
    public function response()
    {
        return $this->response;
    }
    public function txid()
    {
        return $this->txid;
    }
    public function version()
    {
        return $this->version;
    }
    public function dumpfull()
    {
        return $this->dumpfull;
    }
    public function sp_dumpfull()
    {
        return $this->sp_dumpfull;
    }
    public function pingNode($ip,$rip)
    {
        $capi = new Cjdns(CJDNS_API_KEY);
        $ping_r[] = $capi->call("RouterModule_pingNode",array("path"=>$ip));
        if(@$ping_r[0]['result'] == "pong")
        {
            $this->dumpfull = $ping_r;
            $from = $ping_r[0]['from'];
            $extra = null;
            $ts = date('c');
            $from_ip = $from;
            $from_path = $from;
            $ip = substr($from, 0,39);
            $path = substr($from, 40,59);
            $dbh = $this->DB();
            $db = $dbh->prepare('INSERT into pings (ts, ip, nodepath, latency, protocol, result, txid, version, request_ip, extra) VALUES (?,?,?,?,?,?,?,?,?,?);');
            $db->bindParam(1, $ts, PDO::PARAM_STR);
            $db->bindParam(2, $ip, PDO::PARAM_STR);
            $db->bindParam(3, $path, PDO::PARAM_STR);
            $db->bindParam(4, $ping_r[0]['ms'], PDO::PARAM_INT);
            $db->bindParam(5, $ping_r[0]['protocol'], PDO::PARAM_INT);
            $db->bindParam(6, $ping_r[0]['result'], PDO::PARAM_STR);
            $db->bindParam(7, $ping_r[0]['txid'], PDO::PARAM_STR);
            $db->bindParam(8, $ping_r[0]['version'], PDO::PARAM_STR);
            $db->bindParam(9, $rip, PDO::PARAM_STR);
            $db->bindParam(10, $extra, PDO::PARAM_STR);
            if(!$db->execute())
            {
                $this->dumpfull = $db->errorInfo();
                return false;
            }
            unset($db);
            $this->peer_1 = $this->path2ip(substr($from, 40, 44));
            $this->peer_2 = $this->path2ip(substr($from, 45, 49));
            $this->peer_3 = $this->path2ip(substr($from, 50, 54));
            $this->peer_4 = $this->path2ip(substr($from, 55, 59));
            $db = $dbh->prepare('INSERT into nodes (addr, version, latency, first_seen, last_seen, last_checked) VALUES (?,?,?,?,?,?) ON DUPLICATE KEY UPDATE last_seen = ?, last_checked = ?, latency = ?, version = ?;');
            $db->bindParam(1, $ip, PDO::PARAM_STR);
            $db->bindParam(2, intval($ping_r[0]['protocol']), PDO::PARAM_INT);
            $db->bindParam(3, intval($ping_r[0]['ms']), PDO::PARAM_INT);
            $db->bindParam(4, $ts, PDO::PARAM_STR);
            $db->bindParam(5, $ts, PDO::PARAM_STR);
            $db->bindParam(6, $ts, PDO::PARAM_STR);
            $db->bindParam(7, $ts, PDO::PARAM_STR);
            $db->bindParam(8, $ts, PDO::PARAM_STR);
            $db->bindParam(9, intval($ping_r[0]['ms']), PDO::PARAM_INT);
            $db->bindParam(10, intval($ping_r[0]['protocol']), PDO::PARAM_INT);
            if(!$db->execute())
            {
                $this->dumpfull = $db->errorInfo();
            }

            @$this->latency = $ping_r[0]['ms'];
            @$this->result = $ping_r[0]['result'];
            @$this->response = $ping_r[0]['result'];
            @$this->txid = $ping_r[0]['txid'];
            @$this->version = $ping_r[0]['version'];
            return true;
        }
        else 
        {
            /* throw new Exception(var_dump($ping_r, $ip)); */
            return false;
        }
    }
    public function path2ip($path)
    {
//       STRUCTURE:   array(1) {
//   [0]=>
//   array(6) {
//     ["from"]=>
//     string(59) "fcab:c9c8:6bc6:b5ed:b220:f932:f228:7d5a@0000.0000.006c.319e"
//     ["ms"]=>
//     int(173)
//     ["protocol"]=>
//     int(7)
//     ["result"]=>
//     string(4) "pong"
//     ["txid"]=>
//     string(10) "guFt6TEI0y"
//     ["version"]=>
//     string(7) "unknown"
//   }
// }
        $ts = date('c');
        $capi = new Cjdns(CJDNS_API_KEY);
        $ping_r[] = $capi->call("RouterModule_pingNode",array("path"=>$path, 200));
        @$result = $ping_r[0]['result'];
        if($result === "pong")
        {
            $pre_path = $ping_r[0]['from'];
            $path = substr($pre_path, 0,39);
            $db = $this->DB();
            $db = $db->prepare('UPDATE nodes SET last_seen = ? where addr = ?;');
            $db->bindParam(1, $ts, PDO::PARAM_STR);
            $db->bindParam(2, $path, PDO::PARAM_STR);
            $db->execute();
            return $path;
        }
        else
            return false;
    }
}
$cjdnsApi = new cjdnsapi();

class Node  
{
    public $uid = -1;
    private function DB()
    {

        try
        {
            return new PDO(dbtype.':dbname='.dbname.';host='.dbhost, dbuser, dbpass);
        }
        catch (PDOException $e)
        {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }
    public function genRand($len = 15) {
        $bytes = openssl_random_pseudo_bytes($len, $cstrong);
        $hex   = bin2hex($bytes);
        while ($cstrong) {
            return $hex;
        }
    }
    public function inet6_expand($address)
    {
        $ipparts = explode('::', $address, 2);

        $head = $ipparts[0];
        $tail = isset($ipparts[1]) ? $ipparts[1] : array();

        $headparts = explode(':', $head);
        $ippad = array();
        foreach ($headparts as $val)
        {
            $ippad[] = str_pad($val, 4, '0', STR_PAD_LEFT);
        }
        if (count($ipparts) > 1)
        {
            $tailparts = explode(':', $tail);
            $midparts = 8 - count($headparts) - count($tailparts);

            for ($i=0; $i < $midparts; $i++)
            {
                $ippad[] = '0000';
            }

            foreach ($tailparts as $val)
            {
                $ippad[] = str_pad($val, 4, '0', STR_PAD_LEFT);
            }
        }

        return implode(':', $ippad);
    }
    public function getAll()
    { 
        return $this->getall;
    }
    public function nodeOwner()
    { 
        return $this->owner;
    }
    public function nodeHostname()
    { 
        return $this->hostname;
    }
    public function nodeVersion()
    { 
        return $this->version;
    }
    public function nodeFirstSeen()
    {
        return $this->firstseen;
    }
    public function nodeLastSeen()
    {
        return $this->lastseen;
    }
    public function nodeCity()
    {
        return $this->city;
    }
    public function nodeProvince()
    {
        return $this->province;
    }
    public function nodeCountry()
    {
        return $this->country;
    }
    public function getProtocol()
    {
        return $this->protocol;
    }
    public function peerCount()
    {
        return $this->peerCount;
    }
    public function nodeLatency()
    {
        return $this->node_latency;
    }
    public function publicKey()
    {
        return $this->publicKey;
    }
    public function location()
    {
        return $this->location;
    }
    public function getAllAddr()
    {
        $db = $this->DB();
        $stmt = $db->prepare('SELECT addr as ip from nodes order by last_seen DESC;');
        $stmt->execute();
        $list = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $list;
    }
    public function getPorts($addr)
    {
        $db = $this->DB();
        $stmt = $db->prepare('SELECT ports from nmap where addr = :addr order by ts DESC limit 1;');
        $stmt->bindParam(':addr', $addr, PDO::PARAM_STR);
        $stmt->execute();
        $ports = $stmt->fetch(PDO::FETCH_COLUMN);

        $ports = json_decode($ports);
        return $ports;
    }
    public function txidSender($txid)
    {
        $txid = filter_var($txid);
        $txid_len = strlen($txid);
        if($txid_len < 10 OR $txid_len > 50)
        {
            exit;
        }
        $db = $this->DB(); 
        $stmt = $db->prepare("SELECT sender from messages where txid = ?;");
        $stmt->bindParam(1, $txid, PDO::PARAM_STR);
        $stmt->execute();
        $txid = $stmt->fetch(PDO::FETCH_COLUMN);

        return $txid;
    }
    public function getHostname($addr)
    {
        $addr = filter_var($addr);
        $db = $this->DB(); 
        $stmt = $db->prepare("SELECT hostname from nodes where addr = ?;");
        $stmt->bindParam(1, $addr, PDO::PARAM_STR);
        $stmt->execute();
        $hostname = $stmt->fetch(PDO::FETCH_COLUMN);

        return $hostname;
    }
    public function getPrivacy($addr)
    {
        $addr = filter_var($addr);
        $db = $this->DB(); 
        $stmt = $db->prepare("SELECT privacy from nodes where addr = ?;");
        $stmt->bindParam(1, $addr, PDO::PARAM_STR);
        $stmt->execute();
        $privacy = intval($stmt->fetch(PDO::FETCH_COLUMN));

        return $privacy;
    }
    public function inboxCount($uid)
    {
        $uid = intval(filter_var($uid, FILTER_VALIDATE_INT));
        if($uid < 10 OR $uid > 30)
        {
            exit;
        }
        $db = $this->DB(); 
        $stmt = $db->prepare("SELECT count(*) from messages where recipient = ? and state = 1;");
        $stmt->bindParam(1, $uid, PDO::PARAM_INT);
        $stmt->execute();
        $inbox_count = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $inbox_count;
    }
    public function nodeContact($addr)
    {
        $addr = filter_var($addr);
        $db = $this->DB(); 
        $stmt = $db->prepare("SELECT contacttype from nodes where addr = ?;");
        $stmt->bindParam(1, $addr, PDO::PARAM_STR);
        $stmt->execute();
        $contacttype = intval($stmt->fetch(PDO::FETCH_COLUMN));

        return $contacttype;
    }
    public function pingHistory($ip)
    {
        $db = $this->DB();
        $stmt = $db->prepare("SELECT * from pings where ip = ? order by ts DESC limit 5;");
        $stmt->bindParam(1, $ip, PDO::PARAM_STR);
        $stmt->execute();
        $pings = $stmt->fetchAll();
        if(empty($pings) or is_null($pings))
        {
            $pings = "No ping data available.";
            return $pings;
        }
        else
        {
            echo "<ul class='node-activity'>";
            foreach($pings as $ping)
            {

                $timestamp = $ping['ts'];
// $pre_d = date_create($timestamp);
// $date_year = date_format($pre_d, 'Y');
// $date_month = date_format($pre_d, 'M');
// $date_day = date_format($pre_d, 'd');
                $node_path = $ping['nodepath'];
                $latency = $ping['latency'];
                $protocol = $ping['protocol'];
                echo "<li>
                <div class='ping-response'>
                <span class='latency'>{$latency}</span>
                <span class='unit'>ms</span>
                </div>
                <div class='info'>
                <h5 class='title'><time class='timeago' datetime='{$timestamp}'></time></h5>
                <p class='desc'>Activity: Ping</p>
                </div>
                </li>";
            }
            echo "</ul>";
        }
    }
    public function get($ip)
    {
        $ip = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
        $isNodeCjdns = substr($ip, 0, 2);
        if(!$ip OR $isNodeCjdns !== "fc")
        {
            die;
        }
        $db = $this->DB(); 
        $date = date("c");
// Rate limiting logins.
// Unable to implement until Activitylog() is finished.
// $stmt = $db->prepare("select count(id), date from activitylog where ip = ? and type = 5 and date >= DATE_SUB(?, interval 1 minute);");
// $stmt->bindParam(1, $ip, PDO::PARAM_STR);
// $stmt->bindParam(2, $date, PDO::PARAM_STR);
// $stmt->execute();
// $ratelimitminute = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = $db->prepare("SELECT * from nodes where addr = ?;");
        $stmt->bindParam(1, $ip, PDO::PARAM_STR);
        if(!$stmt->execute())
        {
            return false;
        }
        $node = $stmt->fetch(PDO::FETCH_ASSOC);
        if(empty($node))
        {
            $capi = new cjdnsApi();
            $capi->pingNode($ip, 'fcbf:7bbc:32e4:716:bd00:e936:c927:fc14');
            return false;
        }
        $this->verified_type = null;
        if(!is_null($node['addr_v_type']))
        {
            $addrvt = $node['addr_v_type'];
            switch ($addrvt) {
                case 1:
// Boilerplate verification
                $verified_type = "This node has been verified and noted for its historical importance to the network.";
                break;
// Boilerplate verification
                case 2:
                $verified_type = "This node has been verified as one of the first nodes on the network.";
                break;

                default:
                $verified_type = "Undefined";
                break;
            }
            $this->verified_type = $node['addr_v_type'];
        }
        $this->getall = $node;
        $this->hostname = $node['hostname'];
        $this->owner = $node['ownername'];
        $this->version = $node['version'];
        $this->city = $node['city'];
        $this->province = $node['province'];
        $this->country = $node['country'];
        $this->firstseen = $node['first_seen'];
        $this->lastseen = $node['last_seen'];            
        $this->ownername = $node['ownername']; 
        $this->protocol = intval($node['cjdns_protocol']);
        $this->verified = $node['addr_verified'];
        $this->verifiedType = $node['addr_v_type'];
        $this->publicKey = htmlentities($node['public_key']);
        $this->location = htmlentities($node['country']);
        if($node['latency'] == 0)
        {
            $this->node_latency = "?";
        }
        else
        {
            $this->node_latency = $node['latency'];
        }
        return true;  
    }

    public function getNode($ip) {
        $db = $this->DB();
        $stmt = $db->prepare('SELECT * from nodes where addr = :ip');
        $stmt->bindParam(':ip', $ip, PDO::PARAM_STR);
        if(!$stmt->execute()) {
            return false;
        }
        $resp = $stmt->fetch(PDO::FETCH_ASSOC);
        return (object) $resp;
    }
    public function postUpdate($type, $value, $ip) {

        if(!$ip or strlen($ip) !== 39)
        {
            return false;
        }
        $type = filter_var($type);
// mfw I make this filter and forget about it :|
        $accepted_types = ['hostname', 'ownername', 'public_key', 'country', 'map_privacy', 'lat', 'lng', 'msg_enabled', 'msg_privacy', 'api_enabled'];

        if(!in_array($type, $accepted_types)) {
            return false;
        }
        $db = $this->DB();
        $pdoType = PDO::PARAM_STR;
        switch ($type) {
            case 'hostname':
            $stmt = $db->prepare('UPDATE nodes set hostname = ? where addr = ?');
            break;
            case 'ownername':
            $stmt = $db->prepare('UPDATE nodes set ownername = ? where addr = ?;');
            break;
            case 'public_key':
            $stmt = $db->prepare('UPDATE nodes set public_key = ? where addr = ?;');
            break;
            case 'country':
            $stmt = $db->prepare('UPDATE nodes set country = ? where addr = ?;');
            break;
            case 'map_privacy':
// Valid Privacy Level
            $privacy_levels = [ 1,2,3 ]; 
            if(!in_array($value, $privacy_levels)) { return false; }

            $stmt = $db->prepare('UPDATE nodes SET map_privacy = ?  WHERE addr = ?;');
            $pdoType = PDO::PARAM_INT;
            break;
            case 'lat':
            $stmt = $db->prepare('UPDATE nodes set lat = ? where addr = ?;');
            break;
            case 'lng':
            $stmt = $db->prepare('UPDATE nodes set lng = ? where addr = ?;');
            break;
            case 'msg_enabled':
            $value = (is_int($value)) ? $value : 1;
            $stmt = $db->prepare('UPDATE nodes set msg_enabled = ? where addr = ?;');
            $pdoType = PDO::PARAM_INT;
            break;
            case 'msg_privacy':
// Valid Privacy Level
            $msg_privacy_levels = [ 1,2,3 ]; 
            if(!in_array($value, $msg_privacy_levels)) { return false; }

            $stmt = $db->prepare('UPDATE nodes SET msg_privacy = ?  WHERE addr = ?;');
            $pdoType = PDO::PARAM_INT;
            break;
            case 'api_enabled':
// Valid Privacy Level
            $msg_privacy_levels = [ 1,2 ]; 
            if(!in_array($value, $msg_privacy_levels)) { return false; }
            if($value === 2) {
                $keyid = $this->genRand(20);
                $secretkey = $this->genRand(28);
                $sth = $db->prepare('UPDATE nodes set api_keyid = ?, api_secretkey = ? where addr = ?;');
                $sth->bindParam(1, $keyid);
                $sth->bindParam(2, $secretkey);
                $sth->bindParam(3, $ip);
                if(!$sth->execute()) { return false; }
            }
            $stmt = $db->prepare('UPDATE nodes SET api_enabled = ?  WHERE addr = ?;');
            $pdoType = PDO::PARAM_INT;
            break;

            default:
            break;
        }
        $stmt->bindParam(1, $value, $pdoType);
        $stmt->bindParam(2, $ip);
        if(!$stmt->execute()) {
            throw new Exception(var_dump($db->errorInfo()));
        }
        return true;
    }
    public function pingGraph($ip)
    {
        if(!$ip or strlen($ip) !== 39)
        {
            return false;
        }
        $db = $this->DB();
        $stmt = $db->prepare("(SELECT ts, latency FROM pings WHERE ip = :ip ORDER BY ts DESC LIMIT 16) order by ts");    
        $stmt->bindParam(':ip', $ip);   
        $stmt->execute();
        $vpds = $stmt->fetchAll();
        $ngraph = array();

        foreach($vpds as $g)
        {
            $tvg = date("Y-m-d H:i:s", strtotime($g['ts']));
            $gvt = $g['latency'];
            $ngraph[] = array('x'=>$tvg, 'y'=>$gvt);
        }
        return $ngraph;
    }
    public function versionGraph($ip)
    {
        if(!$ip or strlen($ip) !== 39)
        {
            return false;
        }
        $db = $this->DB();
        $stmt = $db->prepare("SELECT ts, version FROM routing WHERE ip = :ip ORDER BY ts ASC LIMIT 20");    
        $stmt->bindParam(':ip', $ip);   
        $stmt->execute();
        $vpds = $stmt->fetchAll();
        $ngraph = array();

        foreach($vpds as $g)
        {
            $tvg = date("Y-m-d H:i:s", strtotime($g['ts']));
            $gvt = $g['version'];
            $ngraph[] = array('x'=>$tvg, 'y'=>$gvt);
        }
        return $ngraph;
    }
    public function uid2Node($uid)
    {
        $uid = filter_var($uid, FILTER_VALIDATE_INT);
// add $options array w/ min-max range ! $isNodeCjdns = substr($ip, 0, 2);
        if(!$uid)
        {
            die;
        }
        $db = $this->DB(); 
        $date = date("Y-m-d H:i:s");
// Rate limiting logins.
// Unable to implement until Activitylog() is finished.
// $stmt = $db->prepare("select count(id), date from activitylog where ip = ? and type = 5 and date >= DATE_SUB(?, interval 1 minute);");
// $stmt->bindParam(1, $ip, PDO::PARAM_STR);
// $stmt->bindParam(2, $date, PDO::PARAM_STR);
// $stmt->execute();
// $ratelimitminute = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = $db->prepare("SELECT addr from nodes where uid = ?;");
        $stmt->bindParam(1, $uid, PDO::PARAM_INT);
        $stmt->execute();
        $node = $stmt->fetch(PDO::FETCH_COLUMN);
        return $node;  
    }
    public function addr2UID($addr)
    {
// add $options array w/ min-max range ! $isNodeCjdns = substr($ip, 0, 2);
        if(!$addr)
        {
            die;
        }
        $db = $this->DB(); 
        $stmt = $db->prepare("SELECT uid from nodes where addr = ?;");
        $stmt->bindParam(1, $addr, PDO::PARAM_STR);
        $stmt->execute();
        $uid = intval($stmt->fetch(PDO::FETCH_COLUMN));
        return $uid;  
    }
    public function updateHostname($addr,$hostname)
    {
        $h_len = strlen(filter_var($hostname));
        if(!empty($addr) AND !empty($hostname) AND $h_len > 40 OR $h_len < 6 )
        {
            return false;
        }
        $dbo = $this->DB(); 

        $db = $dbo->prepare("update nodes set hostname = :hostname where addr = :addr;");
        $db->bindParam(':addr', $addr, PDO::PARAM_STR);
        $db->bindParam(':hostname', $hostname, PDO::PARAM_STR);
        if(!$db->execute())
        {
            return false;
        }
        return true;
    }
    public function updateOwnername($addr,$ownername)
    {
        $h_len = strlen(filter_var($ownername));
        if(!empty($addr) AND !empty($ownername) AND $h_len > 16 OR $h_len < 2 )
        {
            return false;
        }
        $dbo = $this->DB();

        $db = $dbo->prepare("update nodes set ownername = :ownername where addr = :addr;");
        $db->bindParam(':addr', $addr, PDO::PARAM_STR);
        $db->bindParam(':ownername', $ownername, PDO::PARAM_STR);
        if(!$db->execute())
        {
            return false; 
        }
        return true;
    }
    public function updateNodepublickey($addr,$nodepublickey)
    {
        $h_len = strlen(filter_var($nodepublickey));
        if(empty($addr) OR empty($nodepublickey))
        {
            throw new Exception(var_dump($addr, $nodepublickey));
        }
        $dbo = $this->DB();

        $db = $dbo->prepare("update nodes set public_key = :pubkey where addr = :addr;");
        $db->bindParam(':addr', $addr, PDO::PARAM_STR);
        $db->bindParam(':pubkey', $nodepublickey, PDO::PARAM_STR);
        if(!$db->execute())
        {
            throw new Exception($db->errorInfo());
        }
        return true;
    }
    public function updateNodelocation($addr,$country)
    {
        if(empty($addr) OR empty($country))
        {
            return false;
        }
        $dbo = $this->DB();

        $db = $dbo->prepare("update nodes set country = :country where addr = :addr;");
        $db->bindParam(':addr', $addr, PDO::PARAM_STR);
        $db->bindParam(':country', $country, PDO::PARAM_STR);
        if(!$db->execute())
        {
            return false; 
        }
        return true;
    }
    public function getPeers($addr)
    {
        $addr = filter_var($addr, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
        $validate_addr = substr($addr, 0, 2);
        if($validate_addr != "fc")
        {
            exit;
        }
        $db = $this->DB(); 
        $stmt = $db->prepare("SELECT * from edges where a = ? or b = ?;");
        $stmt->bindParam(1, $addr, PDO::PARAM_STR);
        $stmt->bindParam(2, $addr, PDO::PARAM_STR);
        $stmt->execute();
        $peers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if($peers = null) {
            return false;
        }
        foreach ($peers as $val) {
            $peer = null;
            if($val['a'] == $addr) {
                $peer = $val['b'];
            }
            else {
                $peer = $val['a'];
            }
            $first_seen = $val['first_seen'];
            $last_seen = $val['last_seen'];
            $monitor = $val['monitor_ip'];
            $ra = array('peer_ip'=>$peer, 'first_seen'=>$first_seen, 'last_seen'=>$last_seen, 'monitor_ip'=>$monitor);
            $resp[] = $ra;
        }
        return $resp;
    }
    public function getPath($ip)
    {
        $db = $this->DB(); 
        $stmt = $db->prepare("SELECT * from pings where ip = ? order by ts DESC limit 1;");
        $stmt->bindParam(1, $ip, PDO::PARAM_STR); 
        if(!$stmt->execute())
        {
            return false;
        }          
        $datas = $stmt->fetch(PDO::FETCH_ASSOC);
        return $datas;
    }
    public function firstSeen($addr)
    {
        $addr = filter_var($addr, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
        $validate_addr = substr($addr, 0, 2);
        if($validate_addr != "fc")
        {
            exit;
        }
        $db = $this->DB(); 
        $stmt = $db->prepare("SELECT first_seen from nodes where addr = ?;");
        $stmt->bindParam(1, $addr, PDO::PARAM_STR);
        $stmt->execute();
        $lastseen = $stmt->fetch(PDO::FETCH_ASSOC);
        return $lastseen['first_seen'];   
    }
    public function lastSeen($addr)
    {
        $addr = filter_var($addr, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
        $validate_addr = substr($addr, 0, 2);
        if($validate_addr != "fc")
        {
            exit;
        }
        $db = $this->DB(); 
        $stmt = $db->prepare("SELECT last_seen from nodes where addr = ?;");
        $stmt->bindParam(1, $addr, PDO::PARAM_STR);
        $stmt->execute();
        $lastseen = $stmt->fetch(PDO::FETCH_ASSOC);
        return $lastseen['last_seen'];   
    }
    public function inbox($uid)
    {
        $options = array(
            'options' => array(
'default' => 0, // value to return if the filter fails
// other options here
'min_range' => 1,
'max_range' => 1000000
)
            );
        $uid = filter_var($uid, FILTER_VALIDATE_INT, $options);
// add $options array w/ min-max range ! $isNodeCjdns = substr($ip, 0, 2);
        if(!$uid or $uid === 0)
        {
            die;
        }
        $db = $this->DB(); 
        $date = date("Y-m-d H:i:s");
// Rate limiting logins.
// Unable to implement until Activitylog() is finished.
// $stmt = $db->prepare("select count(id), date from activitylog where ip = ? and type = 5 and date >= DATE_SUB(?, interval 1 minute);");
// $stmt->bindParam(1, $ip, PDO::PARAM_STR);
// $stmt->bindParam(2, $date, PDO::PARAM_STR);
// $stmt->execute();
// $ratelimitminute = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = $db->prepare("SELECT * from messages where recipient = ? order by ts DESC;");
        $stmt->bindParam(1, $uid, PDO::PARAM_STR);
        $stmt->execute();
        $messageArray = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(is_null($messageArray) or !$messageArray)
        {
            return false;
        }
        foreach ($messageArray as $msg) {
/*  $msg['state'] types
0 = draft
1 = delivered, unread
2 = read
3 = marked unread
4 = archived
5 = labelled
6 = marked as spam
7 = junk
8 = deleted
9 = marked malicious
10 = undeliverable */
$msg_date_url = date(strtotime($msg['ts']));
$msg_date = date("c",strtotime($msg['ts']));
$msg_sender = unserialize($msg['sender']);
$msg_sender_uid = $msg_sender['uid'];
$msg_sender_name = uid2Username($msg_sender_uid);
if(isset($msg['subject']))
{
    if(strlen(htmlentities($msg['subject'])) > 60)
    {
        $msg_sub = substr(htmlentities($msg['subject']), 0,60).'...';
        $big_subject = true;
    }
    else
    {
        $msg_sub = filter_var($msg['subject']);
        $big_subject = false;
    }
}
switch ($msg['state']) {
    case 0:
    $show = false;
    $msg_unread = null;
    $msg_status = "Undeliverable.";
    $msg_sender_uid = null;
    $msg_sender_name = null;
    $msg_subject = null;
    $msg_body = null;
    $msg_txid = null;
    $sender_state = null;
    $recipient_state = null;
    $msg_labels = null;
    $metadata = null;
    break;
    case 1:
    $show = true;
    $msg_unread = ' class="unread"';
    $msg_status = "Delivered, unread.";
    $msg_subject = $msg_sub;
    $msg_body = $msg['body'];
    $msg_txid = $msg['txid'];
    $sender_state = $msg['sender_state'];
    $recipient_state = $msg['recipient_state'];
    $msg_labels = $msg['labels'];
    $metadata = $msg['metadata'];
    break;
    case 2:
    $show = true;
    $msg_unread = null;
    $msg_status = "Delivered, read.";
    $msg_subject = $msg_sub;
    $msg_body = $msg['body'];
    $msg_txid = $msg['txid'];
    $sender_state = $msg['sender_state'];
    $recipient_state = $msg['recipient_state'];
    $msg_labels = $msg['labels'];
    $metadata = $msg['metadata'];
    break;
    case 3:
    $show = true;
    $msg_unread = ' class="unread"';
    $msg_status = "Delivered, marked unread.";
    $msg_subject = $msg_sub;
    $msg_body = $msg['body'];
    $msg_txid = $msg['txid'];
    $sender_state = $msg['sender_state'];
    $recipient_state = $msg['recipient_state'];
    $msg_labels = $msg['labels'];
    $metadata = $msg['metadata'];
    break;
    case 4:
    $show = true;
    $msg_unread = null;
    $msg_status = "Delivered, archived.";
    $msg_subject = $msg_sub;
    $msg_body = $msg['body'];
    $msg_txid = $msg['txid'];
    $sender_state = $msg['sender_state'];
    $recipient_state = $msg['recipient_state'];
    $msg_labels = $msg['labels'];
    $metadata = $msg['metadata'];
    break;


    case 8:
    $show = false;
    $msg_unread = null;
    $msg_status = "Deleted.";
    $msg_sender_uid = null;
    $msg_sender_name = null;
    $msg_subject = null;
    $msg_body = null;
    $msg_txid = null;
    $sender_state = null;
    $recipient_state = null;
    $msg_labels = null;
    $metadata = null;
    break;
    default:
# code...
    break;
}
if($show)
{
    echo('<tr'.$msg_unread.'><a href="'.appScheme.'://'.appUrl.'/message/view/'.$uid.'/'.$msg_date_url.'/'.$msg_txid.'">
        <td class="small-col"><input type="checkbox" /></td>
        <td class="name"><a href="'.appScheme.'://'.appUrl.'/message/view/'.$uid.'/'.$msg_date_url.'/'.$msg_txid.'">'.$msg_sender_name.'</a></td>
        <td class="subject"><a href="'.appScheme.'://'.appUrl.'/message/view/'.$uid.'/'.$msg_date_url.'/'.$msg_txid.'">'.$msg_subject.'</a></td>
        <td class="time">'.$msg_date.'</td></a>
        </tr>');
}
}
return true;

}
public function sendMessage($ip,$uid,$recipient,$subject,$body,$metadata)
{
    $recipient = $this->addr2UID($recipient);
    $options = array(
        'options' => array(
'default' => 0, // value to return if the filter fails
// other options here
'min_range' => 1,
'max_range' => 1000000
)
        );
    $sender = serialize(array("ip"=>$ip,"uid"=>$uid));
    $recipient = filter_var($recipient, FILTER_VALIDATE_INT, $options);
// add $options array w/ min-max range ! $isNodeCjdns = substr($ip, 0, 2);
    if(!$recipient or $recipient == 0)
    {
        die;
    }
    $db = $this->DB(); 
$labels = "null"; // FIXME
$txid = bin2hex(openssl_random_pseudo_bytes(18));
$ts = date("Y-m-d H:i:s");
$db = $db->prepare("insert into messages (sender, recipient, subject, body, ts, txid, labels, metadata) VALUES (?, ?, ?, ?, ?, ?, ?, ?);");
$db->bindParam(1, $sender, PDO::PARAM_STR);
$db->bindParam(2, $recipient, PDO::PARAM_INT);
$db->bindParam(3, $subject, PDO::PARAM_STR);
$db->bindParam(4, $body, PDO::PARAM_STR);
$db->bindParam(5, $ts, PDO::PARAM_STR);
$db->bindParam(6, $txid, PDO::PARAM_STR);
$db->bindParam(7, $labels, PDO::PARAM_STR);
$db->bindParam(8, $metadata, PDO::PARAM_STR);
$db->execute();
return true;

}
public function readMessage($txid,$uid,$ip,$ts)
{
// $txid = msg transaction id
// $uid = user_id
// $ip = node ip
// $owner = recipient or sender
    $options = array(
        'options' => array(
'default' => 0, // value to return if the filter fails
// other options here
'min_range' => 1,
'max_range' => 1000000
)
        );
    $recipient = filter_var($uid, FILTER_VALIDATE_INT, $options);
    if(!$uid or $uid === 0 or !$txid)
    {
        die;
    }
    $db = $this->DB(); 
    $tss = date("Y-m-d H:i:s", $ts);
    $stmt = $db->prepare("SELECT * from messages where txid = ? and sender = ? OR recipient = ? and ts = ?;");
    $stmt->bindParam(1, $txid, PDO::PARAM_STR);
    $stmt->bindParam(2, $uid, PDO::PARAM_INT);
    $stmt->bindParam(3, $uid, PDO::PARAM_INT);
    $stmt->bindParam(4, $tss, PDO::PARAM_STR);
    $stmt->execute();
    $messageArray = $stmt->fetch(PDO::FETCH_ASSOC);
    if($messageArray['state']==1)
    {
        $msg_id = intval($messageArray['id']);
        $stmt = $db->prepare("update messages set state = 2 where id = ?;");
        $stmt->bindParam(1, $msg_id, PDO::PARAM_STR);
        $stmt->execute();   
    }
    $this->timestamp = date("c", strtotime($messageArray['ts']));
    $this->subject = htmlentities($messageArray['subject']);
    if(strlen(htmlentities($messageArray['subject'])) > 60)
    {
        $subject_shortened = substr(htmlentities($messageArray['subject']), 0,38);
        $this->subject_short = $subject_shortened;
    }
    else
        $this->subject_short = htmlentities($messageArray['subject']);
    $this->body = htmlentities($messageArray['body']);
    $this->sender_ip = uid2Addr($messageArray['sender']);
    $this->sender_name = uid2Username($messageArray['sender']);
    $msg_sender = unserialize($messageArray['sender']);
    $this->sender_ip = $msg_sender['ip'];
    $this->sender_name = uid2Username($msg_sender['uid']);
    return $messageArray;

}
public function updateProtocol($ip)
{
    $capi = new Cjdns(CJDNS_API_KEY);
    $ip = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
    $isNodeCjdns = substr($ip, 0, 2);
    if(!$ip OR $isNodeCjdns !== "fc")
    {
        die;
    }
    $db = $this->DB(); 
    $date = date("Y-m-d H:i:s");
    $rm_lookup = $capi->call("RouterModule_pingNode", array("path"=>$ip));
    $protocol_v = $rm_lookup['protocol'];

    $stmt = $db->prepare("update nodes set cjdns_protocol = ? where addr = ?;");
    $stmt->bindParam(1, $protocol_v, PDO::PARAM_INT);
    $stmt->bindParam(2, $ip, PDO::PARAM_STR);
    if(!$stmt->execute())
    {
        throw new Exception('Error creating update protocol.');
    }
    return true;
}
}
$node = new node();
class Router
{
    public $uid = -1;
    private function DB()
    {

        try
        {
            return new PDO(dbtype.':dbname='.dbname.';host='.dbhost, dbuser, dbpass);
        }
        catch (PDOException $e)
        {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }
    public function getAll($ip)
    { 
        return $this->getall;
    }
    public function ni_id()
    {
        return $this->ni_id;
    } 
    public function ni_link()
    {
        return $this->ni_link;
    } 
    public function ni_path()
    {
        return $this->ni_path;
    }
    public function ni_ts()
    {
        return $this->ni_ts;
    } 
    public function ni_extra()
    {
        return $this->ni_extra;
    } 
    public function ni_full()
    {
        return $this->ni_full;
    } 
    public function ni_version()
    {
        return $this->ni_version;
    }
    public function stripFunnyAddy($ipath)
    {
        $ipath = filter_var($ipath);
        $ipath_len = strlen($ipath);
        if($ipath_len !== 59)
        {
            $this->errorMessage = "Invalid length";
            return false;
        }
        $ipath = substr($ipath, 0, 39);
        return $ipath;
    }
// For Debugging Purposes Only
//
// public function insertRemoteData($data)
// {
//     $db = $this->DB();
//     $db = $db->prepare('INSERT into edgetest (data) VALUES (?);');
//     $db->bindParam(1, $data, PDO::PARAM_STR);
//     $db->execute();
// }
    public function insertRemoteEdges($a, $b, $origin)
    {
        $c = date('c');
        $db = $this->DB();
        $db = $db->prepare('INSERT into edges (a, b, first_seen, last_seen, monitor_ip) VALUES (?,?,?,?,?) ON DUPLICATE KEY UPDATE last_seen = ?;');
        $db->bindParam(1, $a, PDO::PARAM_STR);
        $db->bindParam(2, $b, PDO::PARAM_STR);
        $db->bindParam(3, $c, PDO::PARAM_STR);
        $db->bindParam(4, $c, PDO::PARAM_STR);
        $db->bindParam(5, $origin, PDO::PARAM_STR);
        $db->bindParam(6, $c, PDO::PARAM_STR);
        $db->execute();
    }
    public function txidSender($txid)
    {
        $txid = filter_var($txid);
        $txid_len = strlen($txid);
        if($txid_len < 10 OR $txid_len > 50)
        {
            exit;
        }
        $db = $this->DB(); 
        $stmt = $db->prepare("SELECT sender from messages where txid = ?;");
        $stmt->bindParam(1, $txid, PDO::PARAM_STR);
        $stmt->execute();
        $txid = $stmt->fetch(PDO::FETCH_COLUMN);

        return $txid;
    } 
    public function nodeLatency($ip)
    {
        $db = $this->DB();
        $db = $db->prepare("SELECT link, latency from pings where ip = ? order by ts DESC limit 1;");
        $db->bindParam(1, $ip, PDO::PARAM_STR);
        $db->execute();
        $pings = $db->fetch();
        $latency = $pings['latency'];
        return $latency;
    }
    public function allKnownNodes($page, $orderby)
    {
        $db = $this->DB();
        $options = array(
            'results_per_page' => 30,
            'url' => "?page=*VAR*&ob={$orderby}",
            'db_handle' => $db,
            'class_ul'                      => 'pagination',
            'class_dead_links' => 'disabled',  
            'class_live_links' => '',  
            'class_current_page' => 'active',  
            'current_page_is_link'          => false,
            'show_links_first_last'         => false,  
            'show_links_prev_next'          => false,  
            'show_links_first_last_if_dead' => false,  
            'show_links_prev_next_if_dead'  => false,  
            'max_links_between_ellipses'    => 5,      
            'max_links_outside_ellipses'    => 1,  
            'db_conn_type'                  => 'pdo',
            'using_bound_values' => true,
            'using_bound_params' => true
            );
        $page = isset($page) ? $page : 1;
        $order_by = isset($orderby) ? $orderby : 'last_seen DESC';
        switch ($order_by) {
            case 1:
            $order_by = 'last_seen DESC';
            break;
            case 2:
            $order_by = 'last_seen ASC';
            break;
            case 3:
            $order_by = 'first_seen DESC';
            break;
            case 4:
            $order_by = 'first_seen ASC';
            break;
            case 5:
            $order_by = 'latency DESC';
            break;
            case 6:
            $order_by = 'latency ASC';
            break;
            case 7:
            $order_by = 'version DESC';
            break;
            case 8:
            $order_by = 'version ASC';
            break;

            default:
            $order_by = 'last_seen DESC';
            break;
        }
        try
        {
            $paginate = new pagination($page, "select * from nodes order by {$order_by}",$options);

        }
        catch(paginationException $e)
        {
            exit();
        }
        if($paginate->success == true)
        {
            $paginate->bindParam(1, $order_by, PDO::PARAM_STR);
            $paginate->execute();  
            $result = $paginate->resultset->fetchAll();
            if( $result > 0)
            {
                echo '<table class="table table-striped table-bordered">
                <thead>
                <tr>
                <th>IP</th>
                <th>Latency</th>
                <th>Last Seen</th>
                </tr>
                </thead>
                <tbody>';
                foreach($result as $n)
                {
                    $ip = $n['addr'];
                    $ip_len = strlen($ip);
                    if($ip_len !== 39)
                    {
                        return false; 
                    }
                    else
                    {
                        $ip_len = null;
                    }
                    $latency = $n['latency'];
                    $first_seen = $n['first_seen'];
                    $last_seen = $n['last_seen'];
                    $version = $n['version'];
                    $hostname = isset($n['hostname']) ? '</a><p class="lead">'.htmlentities($n['hostname']).'</p>' : '</a>';
                    echo "<tr>
                    <td><strong><a href='/node/{$ip}' id='node_addr'>{$ip}</a></strong></td>
                    <td>{$latency} ms <span class='label label-default'>v {$version}</span></td>
                    <td><time class='timeago' datetime='{$last_seen}'></time></td>";

                }
                echo '</tbody></table>';
                echo $paginate->links_html;
            }
        }   
    }
    public function knownNode($ip)
    {
        $db = $this->DB();
        $ip = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
        $isNodeCjdns = substr($ip, 0, 2);
        if(!$ip OR $isNodeCjdns !== "fc")
        {
            die;
        }      
        $db = $db->prepare('SELECT count(addr) from nodes where addr = ?;');
        $db->bindParam(1, $ip, PDO::PARAM_STR);
        $db->execute();
        $nodes = $db->fetch(PDO::FETCH_COLUMN);
        $result = intval($nodes);
        if($result > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function newHub($nr_ip, $nr_link, $nr_path, $nr_version, $nr_protocol, $nr_latency)
    {
        $db = $this->DB();
        $ip = filter_var($nr_ip, FILTER_VALIDATE_IP);
        $isNodeCjdns = substr($ip, 0, 2);
        if(!$ip OR $isNodeCjdns !== "fc")
        {
            die;
        }
        $link = intval($nr_link);
        $path = filter_var($nr_path);
        $version = intval($nr_version);
        $protocol = intval($nr_protocol);
        $latency = intval($nr_latency);
        $ts = date('c');
        $db = $db->prepare('INSERT into routing (ip, link, nodepath, version, ts) VALUES (:addr, ?, ?, ?, ?);');
        $db->bindParam(':addr', $ip, PDO::PARAM_STR);
        $db->bindParam(2, $link, PDO::PARAM_INT);
        $db->bindParam(3, $path, PDO::PARAM_STR);
        $db->bindParam(4, $protocol, PDO::PARAM_INT);
        $db->bindParam(5, $ts, PDO::PARAM_STR);
        if($db->execute())
        {
            $dbh = $this->DB();
            $db = $dbh->prepare('INSERT into nodes (addr, version, latency, first_seen, last_seen, last_checked) VALUES (:addr,:version,:latency,:ts,:ts,:ts) ON DUPLICATE KEY UPDATE last_seen = :ts, last_checked = :ts, latency = :latency, version = :version;');
            $db->bindParam(':addr', $ip, PDO::PARAM_STR);
            $db->bindParam(':version', $protocol, PDO::PARAM_INT);
            $db->bindParam(':latency', $latency, PDO::PARAM_INT);
            $db->bindParam(':ts', $ts, PDO::PARAM_STR);
            if(!$db->execute())
            {
                return false;
            }
        }
        else
        {
            return  false;
        }
        return true;
    }
    public function updateProtocol($ip)
    {
        $capi = new Cjdns(CJDNS_API_KEY);
        $ip = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
        $isNodeCjdns = substr($ip, 0, 2);
        if(!$ip OR $isNodeCjdns !== "fc")
        {
            die;
        }
        $db = $this->DB(); 
        $date = date("Y-m-d H:i:s");
        $rm_lookup = $capi->call("RouterModule_pingNode", array("path"=>$ip));
        $protocol_v = $rm_lookup['protocol'];

        $stmt = $db->prepare("update nodes set cjdns_protocol = ? where addr = ?;");
        $stmt->bindParam(1, $protocol_v, PDO::PARAM_INT);
        $stmt->bindParam(2, $ip, PDO::PARAM_STR);
        if(!$stmt->execute())
        {
            throw new Exception('Error creating update protocol.');
        }
        return true;
    }
    public function getNodeInfo($ip)
    {
        $db = $this->DB();
        $db = $db->prepare('SELECT * from routing where ip = ? order by ts DESC limit 1;');
        $db->bindParam(1, $ip, PDO::PARAM_STR);
        $db->execute();
        $nodes = $db->fetchAll(PDO::FETCH_ASSOC);
        foreach($nodes as $n)
        {
            $this->ni_id = intval($n['id']);
            $this->ni_link = intval($n['link']);
            $this->ni_path = filter_var($n['nodepath']);
            $this->ni_ts = $n['ts'];
            $this->ni_extra = $n['extra'];
            $this->ni_version = $n['version'];
            $this->ni_full = $n;
            return true;
        }
    }
}
$router = new router();
class Nmapper {
    public $uid = -1;
    private function DB()
    {
        try
        {
            return new PDO(dbtype.':dbname='.dbname.';host='.dbhost, dbuser, dbpass);
        }
        catch (PDOException $e)
        {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }
    public function inet6_expand($address)
    {
        $ipparts = explode('::', $address, 2);

        $head = $ipparts[0];
        $tail = isset($ipparts[1]) ? $ipparts[1] : array();

        $headparts = explode(':', $head);
        $ippad = array();
        foreach ($headparts as $val)
        {
            $ippad[] = str_pad($val, 4, '0', STR_PAD_LEFT);
        }
        if (count($ipparts) > 1)
        {
            $tailparts = explode(':', $tail);
            $midparts = 8 - count($headparts) - count($tailparts);

            for ($i=0; $i < $midparts; $i++)
            {
                $ippad[] = '0000';
            }

            foreach ($tailparts as $val)
            {
                $ippad[] = str_pad($val, 4, '0', STR_PAD_LEFT);
            }
        }

        return implode(':', $ippad);
    }

    public function newScan($addr)
    {
        $db = $this->DB();
        $ip = (filter_var($addr, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) ? true : false;
        $web = (filter_var($addr, FILTER_VALIDATE_URL)) ? true : false;

        $nmap = Nmap::create()->enableIPv6()->scan([ $addr ]);
        $r = json_decode(json_encode($nmap), true);
        if($r = false OR $r = null)
        {
            return false;
        }
        foreach ($r[0]['ports'] as $p) {
            $ports[] .= $p['number'];
        }
        $hostname = filter_var($r[0]['hostnames'][0]['name']);
        $ports = ($ports != null or $ports != false) ? json_encode($ports) : null;
        $r = ($r != null or $r != false) ? json_encode($r) : null;
        $stmt = $db->prepare('insert into nmap (addr, hostname, ports, raw) VALUES(:addr, :host, :ports, :raw);');
        $stmt->bindParam(':addr', $addr, PDO::PARAM_STR);
        $stmt->bindParam(':host', $hostname, PDO::PARAM_STR);
        $stmt->bindParam(':ports', $ports, PDO::PARAM_STR);
        $stmt->bindParam(':raw', $r, PDO::PARAM_STR);
        if($stmt->execute()) 
        {
            return true;
        }
        else
        {
            return false;
        }

    }
}
$nmapper = new Nmapper();
class Api {
    private function DB()
    {
        try
        {
            return new PDO(dbtype.':dbname='.dbname.';host='.dbhost, dbuser, dbpass);
        }
        catch (PDOException $e)
        {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public function getWebservers() {
        $db = $this->DB();

        $stmt = $db->prepare("SELECT DISTINCT addr, ports FROM nmap WHERE ports LIKE '%80%' OR '%443%' ");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }

}
$api = new Api();
class Services {
    private function DB()
    {
        try
        {
            return new PDO(dbtype.':dbname='.dbname.';host='.dbhost, dbuser, dbpass);
        }
        catch (PDOException $e)
        {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }
    public function getGUID(){
        if (function_exists('com_create_guid')){
            return com_create_guid();
        }else{
            mt_srand((double)microtime()*10000);
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);
            $uuid = substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12);
            return $uuid;
        }
    }
    public function getOwnedServices($ip) {
        $db = $this->DB();

        if(strlen($ip) !== 39 OR substr($ip, 0, 2) !== 'fc')
        {
            throw new Exception('INVALID IP.');
        }
        $stmt = $db->prepare('SELECT * from services where ip = :ip');
        $stmt->bindParam(':ip', $ip, PDO::PARAM_STR);
        if(!$stmt->execute())
        {
            throw new Exception('DB ERROR');
        }
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $services;
    }
    public function getAll($page, $orderby) {
        $db = $this->DB();
        $options = array(
            'results_per_page' => 15,
            'url' => "?page=*VAR*&ob={$orderby}",
            'db_handle' => $db,
            'class_ul'                      => 'pagination',
            'class_dead_links' => 'disabled',  
            'class_live_links' => '',  
            'class_current_page' => 'active',  
            'current_page_is_link'          => false,
            'show_links_first_last'         => false,  
            'show_links_prev_next'          => false,  
            'show_links_first_last_if_dead' => false,  
            'show_links_prev_next_if_dead'  => false,  
            'max_links_between_ellipses'    => 5,      
            'max_links_outside_ellipses'    => 1,  
            'db_conn_type'                  => 'pdo',
            'using_bound_values' => true,
            'using_bound_params' => true
            );
        $page = isset($page) ? $page : 1;
        $order_by = isset($orderby) ? $orderby : 'last_seen DESC';
        switch ($order_by) {
            case 1:
            $order_by = 'date_added ASC';
            break;
            case 2:
            $order_by = 'last_seen ASC';
            break;
            case 3:
            $order_by = 'name ASC';
            break;
            case 4:
            $order_by = 'name DESC';
            break;

            default:
            $order_by = 'date_added ASC';
            break;
        }
        try
        {
            $paginate = new pagination($page, "select * from services order by {$order_by}",$options);

        }
        catch(paginationException $e)
        {
            exit();
        }
        if($paginate->success == true)
        {
            $paginate->bindParam(1, $order_by, PDO::PARAM_STR);
            $paginate->execute();  
            $result = $paginate->resultset->fetchAll();
            if( $result > 0)
            {
                echo "<table class=\"table table-striped table-bordered\">
                <thead>
                <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Type</th>
                </tr>
                </thead>
                <tbody>\n";
                foreach($result as $s)
                {
                    $name = htmlentities($s['name']);
                    $desc = htmlentities($s['short_description']);
                    switch ($s['type']) {
                        case 1:
                        $type = 'Website';
                        break;
                        case 2:
                        $type = 'IRCd';
                        break;
                        case 3:
                        $type = 'Mail Server';
                        break;
                        case 4:
                        $type = 'P2P';
                        break;
                        case 5:
                        $type = 'Other';
                        break;    
                        default:
                        $type = 'Undefined';
                        break;
                    }
                    echo "<tr>\n<td><strong><a href='/service/{$s['pid']}'>{$name}</a></strong></td>
                    <td>{$desc}</td>
                    <td>{$type}</td>\n</tr>\n";

                }
                echo '</tbody></table>';
                if((int)$paginate->total_pages > 1) {
                    echo $paginate->links_html;
                }
            }
        }   
        return;
    }
    public function getService($id) {
        if(!preg_match('/^(\{)?[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}(?(1)\})$/i', $id)) {
            throw new Exception('Invalid ID.');
        }
        $db = $this->DB();

        $stmt = $db->prepare('SELECT * from services where pid = :pid;');
        $stmt->bindParam(':pid', $id, PDO::PARAM_STR);
        if(!$stmt->execute()) {
            return false;
        }
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        return $results;

    }

    public function addNew($name, $uri, $type, $desc, $adult, $ip) {

        $db = $this->DB();
        $guid = $this->getGUID();
        $date = date('c');

        $stmt = $db->prepare("INSERT into services (pid, type, uri, ip, name, date_added, last_seen, short_description, adult_content) VALUES(:pid, :type, :uri, :ip, :name, :date_added, :last_seen, :description, :adult);");
        $stmt->bindParam(':pid', $guid, PDO::PARAM_STR);
        $stmt->bindParam(':type', $type, PDO::PARAM_INT);
        $stmt->bindParam(':uri', $uri, PDO::PARAM_STR);
        $stmt->bindParam(':ip', $ip, PDO::PARAM_STR);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':date_added', $date, PDO::PARAM_STR);
        $stmt->bindParam(':last_seen', $date, PDO::PARAM_STR);
        $stmt->bindParam(':description', $desc, PDO::PARAM_STR);
        $stmt->bindParam(':adult', $adult, PDO::PARAM_INT);
        if(!$stmt->execute()) {
            throw new Exception("{$stmt->errorInfo()}");
            return false;
        }
        return true;
    }

}
$services = new Services();
class People {
    private function DB()
    {
        try
        {
            return new PDO(dbtype.':dbname='.dbname.';host='.dbhost, dbuser, dbpass);
        }
        catch (PDOException $e)
        {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }
    public function getUser($id) {
        $db = $this->DB();

        $stmt = $db->prepare("SELECT users.user_id, users.date_created, users.date_modified, users.username, users.email, users.email_verified, users.ip, users.sn_username, users.hypeirc_nickname, users.permission, users.status, users.privacy, profile.bio from users left join profile on users.user_id = profile.user_id where users.username = :username;");
        $stmt->bindParam(':username', $id, PDO::PARAM_STR);
        if(!$stmt->execute()) {
            return false;
        }
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->uid = intval($r['user_id']);
        $this->joined = $r['date_created'];
        $this->lastModified = $r['date_modified'];
        $this->email = filter_var($r['email'], FILTER_VALIDATE_EMAIL);
        $this->emailVerified = $r['email_verified'];
        $this->ip = $r['ip'];
        $this->sn_username = $r['sn_username'];
        $this->permission = intval($r['permission']); 
        $this->status = intval($r['status']);
        $this->privacy = intval($r['privacy']);
        $this->bio = htmlentities($r['bio']);
        return $r;
    }
}
$people = new People();


class Blog {
    private function DB()
    {
        try
        {
            return new PDO(dbtype.':dbname='.dbname.';host='.dbhost, dbuser, dbpass);
        }
        catch (PDOException $e)
        {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }
    public function getPostAuthor($id) {
        $db = $this->DB();
        $stmt = $db->prepare('SELECT username from users where user_id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $name = $stmt->fetch(PDO::FETCH_COLUMN);
        return $name;
    }
    public function getRecentPosts() {
        $db = $this->DB();
        $stmt = $db->prepare('SELECT * from blog order by id DESC limit 10;');
        $stmt->execute();
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $posts;
    }
    public function getBlogPost($year = 2014, $month = 12, $url = false, $id = false) {
        if($id == false && $year !== 2014)
        {
            return false;
        }
        $db = $this->DB();
        if($id !== false && is_int($id))
        {
            $stmt = $db->prepare("SELECT * from blog where id = :id;");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            if(!$stmt->execute()) {
                return false;
            }
            $r = $stmt->fetch(PDO::FETCH_ASSOC);

            return $r;
        }
        else
        {
            $stmt = $db->prepare("SELECT * from blog where year = :year and month = :month and url = :url;");
            $stmt->bindParam(':year', $year, PDO::PARAM_INT);
            $stmt->bindParam(':month', $month, PDO::PARAM_INT);
            $stmt->bindParam(':url', $url, PDO::PARAM_STR);
            if(!$stmt->execute()) {
                return false;
            }
            $r = $stmt->fetch(PDO::FETCH_ASSOC);

            return $r;
        }
    }
    public function newBlogPost($title, $url, $tags, $body, $uid, $snippet = false, $ts = false)
    {
        $db = $this->DB();
        $title_short = (strlen($title) > 30) ? substr($title, 0, 30).'...' : $title;
        $snippet = ($snippet) ? $snippet : substr($body, 0, 120);
        $ts = ($ts) ? filter_var($ts) : date('c');
        $p_ts = date_parse($ts);
        $ts_year = $p_ts['year'];
        $ts_month = $p_ts['month'];
        $meta = serialize(array('title_short'=>$title_short, 'body_snippet'=>$snippet));
        $stmt = $db->prepare('INSERT into blog (ts, year, url, month, title, tags, uid, meta, body) VALUES(:ts, :year, :url, :month, :title, :tags, :uid, :meta, :body);');
        $stmt->bindParam(':ts', $ts, PDO::PARAM_STR);
        $stmt->bindParam(':year', $ts_year, PDO::PARAM_INT);
        $stmt->bindParam(':url', $url, PDO::PARAM_STR);
        $stmt->bindParam(':month', $ts_month, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':tags', $tags, PDO::PARAM_STR);
        $stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
        $stmt->bindParam(':meta', $meta, PDO::PARAM_STR);
        $stmt->bindParam(':body', $body, PDO::PARAM_STR);
        if(!$stmt->execute())
        {
            throw new Exception('Error in insert.');
            return false;
        }
        return true;
    }
}
$blog = new Blog();

?>