<?php
/**
*  Service Class
*/
namespace App\Models;

use \App\Config\App as Config;
use PDO;
use \App\Utils\Pagination;

class Service  {

	protected $db;
	function __construct()
	{
		$this->db = new \App\Models\Database();
	}
	public function getGUID(){
		if (function_exists('com_create_guid')) {
			return com_create_guid();
		} else {
			mt_srand((double)microtime()*10000);
			$charid = strtoupper(md5(uniqid(rand(), true)));
			$hyphen = chr(45);
			$uuid = substr($charid, 0, 8).$hyphen
			.substr($charid,  8,  4).$hyphen
			.substr($charid, 12,  4).$hyphen
			.substr($charid, 16,  4).$hyphen
			.substr($charid, 20, 12);
			return $uuid;
		}
	}
	public function getOwnedServices($ip) {
		$db = $this->db;

		if(strlen($ip) !== 39 OR substr($ip, 0, 2) !== 'fc') {
			return false;
		}
		$stmt = $db->prepare('SELECT pid, uri, name from services where ip = :ip');
		$stmt->bindParam(':ip', $ip, PDO::PARAM_STR);
		if(!$stmt->execute()) {
			throw new \Exception('DB ERROR');
		}
		$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $services;
	}
	public function getAll($page, $orderby = 1) {
		$db = $this->db;
		$options = array(
			'results_per_page'              => 15,
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
		$order_by = is_int($orderby) ? $orderby : 'id ASC';
		switch ($order_by) {
			case 1:
			$order_by = 'id ASC';
			break;
			case 2:
			$order_by = 'id DESC';
			break;
			case 3:
			$order_by = 'name ASC';
			break;
			case 4:
			$order_by = 'name DESC';
			break;
			default:
			$order_by = 'id DESC';
			break;
		}
		try
		{
			$paginate = new Pagination($page, "SELECT * from services ORDER BY {$order_by}",$options);

		}
		catch(paginationException $e)
		{
			exit();
		}
		if($paginate->success == true) {
			$paginate->execute();
			$result = $paginate->resultset->fetchAll();
			if( $result > 0) {
				echo "<table class=\"table table-striped table-bordered\">
				<thead>
				<tr>
				<th>Name</th>
				<th>Description</th>
				<th>Type</th>
				<th>Added</th>
				</tr>
				</thead>
				<tbody>\n";
				foreach($result as $s) {
					$name = htmlentities($s['name']);
					$desc = htmlentities($s['short_description']);
					$added = "<time class=\"timeago\" datetime=\"{$s['date_added']}\"></time>";
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
					<td>{$type}</td>\n
					<td>{$added}</td>\n

					</tr>\n";
				}
				echo '</tbody></table>';
				if((int)$paginate->total_pages > 1) {
					echo $paginate->links_html;
				}
			} /* /foreach */
		} /* /if $result */
		return;
	}

	public function getService($id) {
		if(!preg_match('/^(\{)?[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}(?(1)\})$/i', $id)) {
			throw new Exception('Invalid ID.');
		}
		$db = $this->db;

		$stmt = $db->prepare('SELECT * from services where pid = :pid;');
		$stmt->bindParam(':pid', $id, PDO::PARAM_STR);
		if(!$stmt->execute()) { return false; }
		$results = $stmt->fetch(PDO::FETCH_ASSOC);

		return $results;

	}

	public function addNew($name, $uri, $type, $desc, $adult, $ip) {

		$guid = $this->getGUID();
		$date = date('c');

		$stmt = $this->db->prepare("INSERT into services (pid, type, uri, ip, name, date_added, last_seen, short_description, adult_content) VALUES(:pid, :type, :uri, :ip, :name, :date_added, :last_seen, :description, :adult);");
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
			throw new Exception("{$stmt->errorInfo()}"); return false;
		}
		return true;
	}

}
$service = new Service();