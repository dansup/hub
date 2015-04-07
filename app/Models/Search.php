<?php
/**
*  Search Class
*/
namespace App\Models;
use PDO;
$pagination = new \App\Utils\Pagination;

class Search  {

	function __construct()
	{
		$this->db = new \App\Models\Database();
	}
	public function totalResults()
	{
		return $this->totalResults;
	}
	public function searchQuery($query, $page, $operator=null, $referer=null, $txid, $uid)
	{
		$query = urldecode($query);
		$options = array(  
			'results_per_page'              => 10,  
			'max_pages_to_fetch'            => 10000,  
			'url'                           => "?q={$query}&op={$operator}&pn=*VAR*&ref={$referer}&uuid={$txid}&uid={$uid}", 
			'url_page_number_var'           => '*VAR*',   
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
				$pagination = new pagination($page, 'SELECT * FROM nodes where addr LIKE :query OR hostname LIKE :query OR ownername LIKE :query OR city LIKE :query ORDER BY last_seen DESC', $options);  
			}
			catch(paginationException $e)
			{
				echo $e;
				exit();
			}

			$pagination->bindParam(':query', '%'.$query.'%', PDO::PARAM_STR);  

			$pagination->execute();

			if($pagination->success === true)  
			{  

				$result = $pagination->resultset->fetchAll();
				$this->totalResults = $pagination->total_results;  
				echo '<div class="col-xs-12"><h1>Results for <span style="font-weight:200;font-style:italic;">'.$query.'</span><span style="float:right!important;"><span style="font-weight:200;">'.$pagination->total_results.'</span> results</span></h1><hr></div>';
				if($pagination->total_results == 0 OR empty($pagination->total_results))
				{
					$no_results = "<div class='col-xs-12 text-center'><h2 class='lead'>No results found.</h2></div>";
					echo $no_results;
				}
				foreach($result as $row)  
				{  
					$ip = $row['addr'];
					$ip_url = "/node/{$ip}";
					$hostname = isset($row['hostname']) ? ' &nbsp;&nbsp;'.htmlentities($row['hostname']) : null;
					$last_seen = $row['last_seen'];
					$latency = !is_null($row['latency']) ? $row['latency'] : '?';
					$version = !is_null($row['version']) ? $row['version'] : '?';
					echo "<div class=\"media\">
					        
					      <div class=\"media-body\">
					        <h4 class=\"media-heading\"><a href=\"{$ip_url}\">{$row['addr']} </a></h4>
					       <span class=\"label label-default\">{$latency} ms</span>&nbsp;<span class=\"label label-default\">Protocol: {$version}</span></span><span class=\"search-result-lastseen\" style=\"padding-left:30px;\">last seen: <time class=\"timeago\" datetime=\"{$last_seen}\"></time>
					      </div>
					     
					    </div><hr>";
				}  


				echo $pagination->links_html;  

			}
	}
}
