<?php
require_once(__DIR__.'/../libs/pagination.php');
/**
*  Node Class
*/
class Node extends PDO
{
	
	function __construct()
	{
		try
		{
		$this->db = new PDO(DB_TYPE.':dbname='.DB_NAME.';host='.DB_HOST, DB_USER, DB_PASS);
		}
		catch (PDOException $e)
		{
		throw new Exception( 'Connection failed: ' . $e->getMessage() );
		}
	}
	public function genRand($len = 15) 
	{
	        $bytes = openssl_random_pseudo_bytes($len, $cstrong);
	        $hex   = bin2hex($bytes);
	        while ($cstrong) {
	            return $hex;
	        }
	}
	public function allKnownNodes($page, $orderby)
	{
	        $db = $this->db;
	        $options = array(
	            'results_per_page'              => 30,
	            'url'                           => "?page=*VAR*&ob={$orderby}",
	            'db_handle'                     => $db,
	            'class_ul'                      => 'pagination',
	            'class_dead_links'              => 'disabled',
	            'class_live_links'              => '',
	            'class_current_page'            => 'active',
	            'current_page_is_link'          => false,
	            'show_links_first_last'         => false,
	            'show_links_prev_next'          => false,
	            'show_links_first_last_if_dead' => false,
	            'show_links_prev_next_if_dead'  => false,
	            'max_links_between_ellipses'    => 5,
	            'max_links_outside_ellipses'    => 1,
	            'db_conn_type'                  => 'pdo',
	            'using_bound_values'            => true,
	            'using_bound_params'            => true
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

	        if($paginate->success == true) {
	            $paginate->bindParam(1, $order_by, PDO::PARAM_STR);
	            $paginate->execute();
	            $result = $paginate->resultset->fetchAll();
	            if( $result > 0) {
	                echo '<table class="table table-striped table-bordered">
	                <thead>
	                    <tr>
	                        <th>IP</th>
	                        <th>Latency</th>
	                        <th>Last Seen</th>
	                    </tr>
	                </thead>
	                <tbody>';
	                foreach($result as $n) {
	                    $ip = $n['addr'];
	                    $ip_len = strlen($ip);

	                    if($ip_len !== 39) {
	                        return false;
	                    } else {
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
	                } /* /foreach */
	                echo '</tbody></table>';
	                echo $paginate->links_html;
	            }
	        } /* /if $paginate-> */
	}
	public function get($ip)
	    {
	        $db = $this->db;
	        $stmt = $db->prepare("SELECT * from nodes where addr = :ip");
	        $stmt->bindParam(':ip', $ip, PDO::PARAM_STR);
	        if(!$stmt->execute())
	        {
	            return false;
	        }
	        $node = $stmt->fetch(PDO::FETCH_ASSOC);
	      /*  if(empty($node))
	        {
	            $capi = new cjdnsApi();
	            $capi->pingNode($ip, 'fcbf:7bbc:32e4:716:bd00:e936:c927:fc14');
	            return false;
	        }*/
	       
	    return (object) $node;  
	}
	public function getLatencyGraph($ip)
	    {
	        if(!$ip or strlen($ip) !== 39)
	        {
	            return false;
	        }
	        $db = $this->db;
	        $stmt = $db->prepare("(SELECT ts, latency FROM pings WHERE ip = :ip ORDER BY ts DESC LIMIT 16) order by ts");    
	        $stmt->bindParam(':ip', $ip);   
	        if(!$stmt->execute()) {
	        	return false;
	        }
	        $graphdata = $stmt->fetchAll();
	        $lgraph = [];

	        foreach($graphdata as $plot)
	        {
	            $timestamp = date("Y-m-d H:i:s", strtotime($plot['ts']));
	            $latency = $plot['latency'];
	            $lgraph[] = array('x'=>$timestamp, 'y'=>$latency);
	        }
	        return $lgraph;
	}
	public function getPeers($ip)
            {

	        $db = $this->db; 
	        $resp = null;
	        $stmt = $db->prepare("SELECT * from edges where a = :ip or b = :ip;");
	        $stmt->bindParam(':ip', $ip, PDO::PARAM_STR);
	        if(!$stmt->execute()) {
		return false;
	        }

	        $peers = $stmt->fetchAll(PDO::FETCH_ASSOC);
	        if($peers = null) {
	            return false;
	        }
	        if(is_array($peers)) {
	        	foreach ($peers as $val) {
	            $peer = null;
	            if($val['a'] == $ip) {
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
	          }
	        return $resp;
	}
	public function postUpdate($type, $value, $ip) 
	{

	        $type = filter_var($type);
	        $accepted_types = ['node_hostname', 'node_ownername', 'node_pubkey', 'node_country', 'node_map_privacy', 'node_lat', 'node_lng', 'node_msg_enabled', 'node_msg_privacy', 'node_api_enabled'];

	        if(!in_array($type, $accepted_types)) {
	            return false;
	        }
	        $db = $this->db;
	        $pdoType = PDO::PARAM_STR;
	        switch ($type) {
	            case 'node_hostname':
	                $stmt = $db->prepare('UPDATE nodes set hostname = ? where addr = ?');
	                break;
	            case 'node_ownername':
	                $stmt = $db->prepare('UPDATE nodes set ownername = ? where addr = ?;');
	                break;
	            case 'node_pubkey':
	                $stmt = $db->prepare('UPDATE nodes set public_key = ? where addr = ?;');
	                break;
	            case 'node_country':
	                $stmt = $db->prepare('UPDATE nodes set country = ? where addr = ?;');
	                break;
	            case 'node_map_privacy':
	                    // Valid Privacy Level
	                    $privacy_levels = [ 1,2,3 ]; 
	                    if(!in_array($value, $privacy_levels)) { return false; }
	                    
	                    $stmt = $db->prepare('UPDATE nodes SET map_privacy = ?  WHERE addr = ?;');
	                    $pdoType = PDO::PARAM_INT;
	                break;
	            case 'node_lat':
	                $stmt = $db->prepare('UPDATE nodes set lat = ? where addr = ?;');
	                break;
	            case 'node_lng':
	                $stmt = $db->prepare('UPDATE nodes set lng = ? where addr = ?;');
	                break;
	            case 'node_msg_enabled':
	                $value = (is_int($value)) ? $value : 1;
	                $stmt = $db->prepare('UPDATE nodes set msg_enabled = ? where addr = ?;');
	                $pdoType = PDO::PARAM_INT;
	                break;
	            case 'node_msg_privacy':
	                    // Valid Privacy Level
	                    $msg_privacy_levels = [ 1,2,3 ]; 
	                    if(!in_array($value, $msg_privacy_levels)) { return false; }
	                    
	                    $stmt = $db->prepare('UPDATE nodes SET msg_privacy = ?  WHERE addr = ?;');
	                    $pdoType = PDO::PARAM_INT;
	                break;
	            case 'node_api_enabled':
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
	            throw new Exception();
	        }
	        return true;
	}
	public function pingNode($ip,$rip)
	{
	        $capi = new CjdnsApi(CJDNS_API_KEY);
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
	            $dbh = $this->db;
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
	                return false;
	            }
	            return true;
	        }
	        else 
	        {
	           /* throw new Exception(var_dump($ping_r, $ip)); */
	            return false;
	        }
	}

}
$node = new Node();