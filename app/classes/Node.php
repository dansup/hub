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
	public function genRand($len = 15) {
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
	        $ip = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
	        $isNodeCjdns = substr($ip, 0, 2);
	        if(!$ip OR $isNodeCjdns !== "fc")
	        {
	            die;
	        }
	        $db = $this->db; 
	        $date = date("c");
	        // Rate limiting logins.
	        // Unable to implement until Activitylog() is finished.
	        // $stmt = $db->prepare("select count(id), date from activitylog where ip = ? and type = 5 and date >= DATE_SUB(?, interval 1 minute);");
	        // $stmt->bindParam(1, $ip, PDO::PARAM_STR);
	        // $stmt->bindParam(2, $date, PDO::PARAM_STR);
	        // $stmt->execute();
	        // $ratelimitminute = $stmt->fetch(PDO::FETCH_ASSOC);
	        $stmt = $db->prepare("SELECT * from nodes where addr = :ip;(SELECT ts, latency FROM pings WHERE ip = :ip ORDER BY ts DESC LIMIT 16) order by ts");
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
	       
	    return $node;  
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
}
$node = new Node();