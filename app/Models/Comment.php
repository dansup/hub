<?php
/**
*  Comment Class
*/
namespace App\Models;

use \App\Config\App as Config;
use PDO;
use \App\Utils\Pagination;

class Comment {
	

    protected $db;

    function __construct()
    {
        $this->db = new \App\Models\Database();
    } 
    
	public function get($type, $identifer, $page = 1) {
		$types = ['node', 'service'];
		$type = (in_array($type, $types)) ? $type : false;
		if(!$type) return false;
		$options = array(  
			'results_per_page'              => 6,  
			'max_pages_to_fetch'            => 10000,  
			'url'                           => "?p=*VAR*",   
			'text_prev'                     => '&lsaquo; Prev',  
			'text_next'                     => 'Next &rsaquo;',  
			'text_first'                    => '&laquo; First',  
			'text_last'                     => 'Last &raquo;',  
			'text_ellipses'                 => '...',  
			'class_ellipses'                => 'ellipses',  
			'class_dead_links'              => 'dead-link',  
			'class_live_links'              => 'live-link',  
			'class_current_page'            => 'current-link',  
			'class_ul'                      => 'pagination text-center',
			'current_page_is_link'          => false,
			'show_links_first_last'         => false,  
			'show_links_prev_next'          => false,  
			'show_links_first_last_if_dead' => false,  
			'show_links_prev_next_if_dead'  => false,  
			'max_links_between_ellipses'    => 3,
			'max_links_outside_ellipses'    => 2,  
			'db_conn_type'                  => 'pdo',
			'db_handle'                     => $this->db,  
			'named_params'                  => false,  
			'using_bound_params'            => true,
			'using_bound_values'            => true  
			);  


			try
			{
				$pagination = new Pagination($page, 'SELECT * FROM comments where destination = :dest ORDER BY id desc', $options);  
			}
			catch(paginationException $e)
			{
				echo $e;
				exit();
			}

			$pagination->bindParam(':dest', $identifer, PDO::PARAM_STR);  

			$pagination->execute();

			if($pagination->success === true)  
			{  

				$result = $pagination->resultset->fetchAll();
				$resp = [];

				// Response Code
				$resp['rc'] = 500;
				// Total Comment Count
				$resp['count'] = (int) $pagination->total_results;

				if($resp['count'] == 0 OR empty($result))
				{
					$no_results = "No Results.";
					$resp['rc'] = 404;
					$resp['data'] = $no_results;
					$resp['pagination'] = null;
				}
				foreach($result as $row)  
				{
					$resp['rc'] = 200;
					$resp['data'][] = $row;  
					$resp['pagination'] = $pagination->links_html;  
				}  


				return $resp;

			}
			else {
				return false;
			}
	}
	public function add($type = 'node', $identifer, $author_ip, $comment_body) {
		$db = $this->db;
		$ts = date('c');
		if(mb_strlen(trim($comment_body)) < 3) {
			return false;
		}
		$comment_body = strip_tags($comment_body);
		$comment_body = (mb_strlen($comment_body) > 140) ? mb_substr($comment_body, 0, 140) : $comment_body;
		$stmt = $db->prepare('INSERT INTO comments (type, destination, author, created, body) VALUES(:type, :dest, :author, :ts, :body);');
		$stmt->bindParam(':type', $type, PDO::PARAM_STR);
		$stmt->bindParam(':dest', $identifer, PDO::PARAM_STR);
		$stmt->bindParam(':author', $author_ip, PDO::PARAM_STR);
		$stmt->bindParam(':ts', $ts, PDO::PARAM_STR);
		$stmt->bindParam(':body', $comment_body, PDO::PARAM_STR);
		if(!$stmt->execute()) {
			return false;
		}
		return true;
	}
}
