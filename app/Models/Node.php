<?php
/**
*  Node Class
*/
namespace App\Models;

use \App\Config\App as Config;
use PDO;
use \App\Utils\Pagination as Pagination;

class Node  {

	protected $db;

	function __construct()
	{
		$this->db = new \App\Models\Database();
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
			$paginate = new \App\Utils\Pagination($page, "select * from nodes order by {$order_by}",$options);

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

					$latency = ($n['latency'] == null) ? 'Undefined' : $n['latency'].' ms ';
					$first_seen = $n['first_seen'];
					$last_seen = $n['last_seen'];
					$version = $n['version'];
					$hostname = isset($n['hostname']) ? '</a><p class="lead">'.htmlentities($n['hostname']).'</p>' : '</a>';
					echo "<tr>
					<td><strong><a href='/node/{$ip}' id='node_addr'>{$ip}</a></strong></td>
					<td>{$latency}  <span class='label label-default'>v {$version}</span></td>
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
		$db->setAttribute(PDO::ATTR_ORACLE_NULLS , PDO::NULL_EMPTY_STRING);
		$stmt = $db->prepare("SELECT addr, hostname, ownername, first_seen, last_seen, country, public_key, version, latency, lat, lng, map_privacy  from nodes where addr = :ip");
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
public function knownNode($ip) {
	$dbh = $this->db->prepare('
		SELECT count(addr)
		FROM nodes 
		WHERE addr = :id 
		OR public_key = :id;');
	$dbh->bindParam(':id', $ip, PDO::PARAM_STR);
	if(!$dbh->execute()) {
		return false;
	}
	$exists = $dbh->fetch();
	$exists = ($exists['count(addr)'] == 1) ? true : false;
	return $exists;
}
public function getLatencyGraph($ip)
{
	if(!$ip or strlen($ip) !== 39)
	{
		return false;
	}
	$db = $this->db;
	$stmt = $db->prepare("(SELECT ts, latency FROM pings WHERE ip = :ip ORDER BY ts DESC LIMIT 20) order by ts");    
	$stmt->bindParam(':ip', $ip);   
	if(!$stmt->execute()) {
		return false;
	}
	$graphdata = $stmt->fetchAll();
	$lgraph = [];

	foreach($graphdata as $plot)
	{
		$timestamp = $plot['ts'];
		$latency = $plot['latency'];
		$lgraph[] = array('x'=>$timestamp, 'y'=>$latency);
	}
	return $lgraph;
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
		$privacy_levels = [ 0,1,2,3 ]; 
		if(!in_array($value, $privacy_levels)) { return false; }

		$stmt = $db->prepare('UPDATE nodes SET map_privacy = ?  WHERE addr = ?;');
		$pdoType = PDO::PARAM_INT;
		break;
		case 'node_lat':
		$stmt = $db->prepare('UPDATE nodes set lat = ? where addr = ?;');
		//$pdoType = PDO::PARAM_STR;
		break;
		case 'node_lng':
		$stmt = $db->prepare('UPDATE nodes set lng = ? where addr = ?;');
		//$pdoType = PDO::PARAM_STR;
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
public function getNodeList() {
	$db = $this->db;
	$stmt = $db->prepare(
		'SELECT addr 
		FROM nodes 
		ORDER BY RAND()
		LIMIT 75;');
	$stmt->execute();
	$list = $stmt->fetchAll(PDO::FETCH_COLUMN);
	return $list;
}
public function getDiscoveryList() {
	$stmt = $this->db->prepare(
		'SELECT addr 
		FROM nodes 
		WHERE addr NOT IN (SELECT ip FROM pings)
		ORDER BY RAND()
		LIMIT 75;');
	$stmt->execute();
	$list = $stmt->fetchAll(PDO::FETCH_COLUMN);
	return $list;
}
public function getPingList() {
	$db = $this->db;
	$stmt = $db->prepare(
		'SELECT DISTINCT(ip) 
		FROM pings 
		ORDER BY RAND()
		LIMIT 150;');
	$stmt->execute();
	$list = $stmt->fetchAll(PDO::FETCH_COLUMN);
	return $list;
}

public function pingNode($ip,$rip)
{
	$capi = new CjdnsApi();
	$ping = $capi->call("RouterModule_pingNode", [
		"path"=>$ip,
		"timeout" => 20000
		]);
	while (isset($ping['result']) && $ping['result'] === "pong") {
		$timestamp = date('c');
		$addr = explode('.', $ping['addr']); 
		$protocol_version = substr($addr[0], 1);
		$path = ($addr[4] == '0000') ? $addr[4] : "{$addr[1]}.{$addr[2]}.{$addr[3]}.{$addr[4]}";
		$latency = (int) $ping['ms'];
		$public_key = "{$addr[5]}.k";

		$dbh = $this->db;
		$db = $dbh->prepare('
			INSERT into pings (ts, ip, nodepath, latency, protocol, request_ip) 
			VALUES (:ts, :addr, :path, :latency, :protocol, :rip);
			INSERT into nodes (addr, version, latency, first_seen, last_seen, last_checked, public_key) 
			VALUES (:addr, :protocol, :latency, :ts, :ts, :ts, :public_key) 
			ON DUPLICATE KEY UPDATE 
			last_seen = :ts, 
			last_checked = :ts, 
			latency = :latency, 
			version = :protocol,
			public_key = :public_key;
			');
		$db->bindParam(":ts", $timestamp, PDO::PARAM_STR);
		$db->bindParam(":addr", $ip, PDO::PARAM_STR);
		$db->bindParam(":path", $path, PDO::PARAM_STR);
		$db->bindParam(":latency", $latency, PDO::PARAM_INT);
		$db->bindParam(":protocol", $protocol_version, PDO::PARAM_INT);
		$db->bindParam(":rip", $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
		$db->bindParam(':public_key', $public_key , PDO::PARAM_STR);
		if(!$db->execute())
		{
			return; 
		}
		return;
	}
	
}
public function getPeers($ip)
{
	$db = $this->db->prepare('
		SELECT DISTINCT(peers.peer_pubkey), nodes.addr, nodes.version 
		FROM peers 
		LEFT JOIN nodes 
		on peers.peer_pubkey = nodes.public_key
		WHERE peers.origin_ip = :addr;
		');
	$db->bindParam(':addr', $ip, PDO::PARAM_STR);
	if(!$db->execute()) {
		return false;
	}
	$peers = $db->fetchAll(PDO::FETCH_ASSOC);
	if(!isset($peers[0])) {
		return false;
	}
	if($peers[0]['addr'] == $ip) {
	unset($peers[0]);
	}
	return (array) $peers;

}
public function setPeers($ip, $rip = false) {
	$capi = new CjdnsApi();
	$gp = $capi->call("RouterModule_getPeers", [
		"path"=>$ip,
		"timeout"=>20000
		]);
	$rip = ( $rip !== false ) ? $rip : $_SERVER['SERVER_ADDR'];
	$gp = ($gp['error'] !== 'not_found' && isset($gp['peers'])) ? $gp : false;
	if($gp !== false) {
		$ts = date('c');
		foreach ($gp['peers'] as $key) {
			$frag = explode('.', $key);
			$pubkey = $frag[5].".k";
			$ver = substr($frag[0], 1);
			$metadata = json_encode([
				'label' => $frag[1].'.'.$frag[2].'.'.$frag[3].'.'.$frag[4],
				'monitor_ip' => $rip
				]);
			$dbh = $this->db->prepare('
				INSERT into peers ( origin_ip, peer_pubkey, ts, version, metadata )
				VALUES  (:addr, :pubkey, :ts, :version, :metadata);
				');
			$dbh->bindParam(':addr', $ip, PDO::PARAM_STR);
			$dbh->bindParam(':pubkey', $pubkey);
			$dbh->bindParam(':ts', $ts);
			$dbh->bindParam(':version', $ver);
			$dbh->bindParam(':metadata', $metadata);
			$dbh->execute();
		}
	}
	else {
		return false;
	}

}
public function getNodeInfo($ip) {
	$dbh = $this->db->prepare(
		'SELECT n.hostname,
		n.ownername,
		n.version,
		n.first_seen,
		n.last_seen,
		n.public_key,
		n.latency, 
		n.avatar_url, 
		n.city,
		n.country,
		n.lat,
		n.lng
		FROM nodes n
		WHERE addr = :addr;');
	$dbh->bindParam(':addr', $ip, PDO::PARAM_STR);
	if(!$dbh->execute()) {
		return json_encode($dbh->errorInfo(), JSON_PRETTY_PRINT);
	}
	$resp = $dbh->fetch(PDO::FETCH_ASSOC);
	$dbh = $this->db->prepare(
		'SELECT DISTINCT(n.addr)
		FROM peers p
		INNER JOIN nodes n
		ON p.peer_pubkey = n.public_key
		WHERE p.origin_ip = :addr;');
	$dbh->bindParam(':addr', $ip, PDO::PARAM_STR);
	if(!$dbh->execute()) {
		return json_encode($dbh->errorInfo(), JSON_PRETTY_PRINT);
	}
	$resp['peers'] = $dbh->fetchAll(PDO::FETCH_COLUMN);
	return $resp;
}
public function capiDumpTable($data) {
	$db = $this->db;

	foreach ($data as $n) {
		$version = substr($n['version'], 1);
		$ts = date('c');
		$dbh = $db->prepare('
			INSERT into nodes (addr, version, first_seen, last_seen, last_checked, public_key) 
			VALUES (:addr, :protocol,:ts, :ts, :ts, :public_key) 
			ON DUPLICATE KEY UPDATE 
			last_seen = :ts, 
			last_checked = :ts, 
			version = :protocol,
			public_key = :public_key;
			');
		$dbh->bindParam(':addr', $n['addr']);
		$dbh->bindParam(':protocol', $version, PDO::PARAM_INT );
		$dbh->bindParam(':ts', $ts);
		$dbh->bindParam(':public_key', $n['key']);
		if(!$dbh->execute()) {
			return;
		}
	}
	return true;
}
}
