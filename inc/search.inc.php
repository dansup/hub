<?php
/* File:    core.inc.php - v 1.0
* Hub - The Hyperboria Analytics Machine
* Created: June 15/2014
* Last Edited: June 16/2014
* classes: cjdnsApi ( api specific actions ), Node (node specific actions ), Router ( app models/views/controllers )
* dependencies: app config, pagination library, cjdns php api (thanks finn)
*
* todo:
*/
require_once('config.inc.php');
require_once('page.inc.php');
require_once("capi/b.inc.php");
require_once("capi/c.inc.php");

class Search {
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
	public function totalResults()
	{
		return $this->totalResults;
	}
	public function searchQuery($query, $page, $operator=null, $referer=null, $txid, $uid)
	{
		$db = $this->DB();
		$query = urldecode($query);
		$options = array(
			'results_per_page'              => 10,
			'max_pages_to_fetch'            => 1000000,
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
			'max_links_between_ellipses'    => 3,      // This MUST be an odd number, or things will break :/
			'max_links_outside_ellipses'    => 2,
			'db_conn_type'                  => 'pdo',  // Can be either: 'mysqli' or 'pdo'
			'db_handle'                     => $db,
			'named_params'                  => false,
			'using_bound_params'            => true,
			'using_bound_values'            => true
		);


		/*
		* Call the class, the var's are:
		*
		* pagination(int $surrent_page, string $query, array $options)
		*/
		try
		{
			$pagination = new pagination($page, 'SELECT * FROM nodes where addr LIKE :query OR hostname LIKE :query OR ownername LIKE :query OR city LIKE :query', $options);
		}
		catch(paginationException $e)
		{
			echo $e;
			exit();
		}

		$pagination->bindParam(':query', '%'.$query.'%', PDO::PARAM_STR);

		$pagination->execute();
		/*
		* If all was successful, we can do something with our results
		*/
		if ($pagination->success === true) {
			/* Get the results */

			$result = $pagination->resultset->fetchAll();
			$this->totalResults = $pagination->total_results;

			echo '<div class="col-xs-12"><h1>Results for <span style="font-weight:200;font-style:italic;">'
				.$query
				.'</span><span style="float:right!important;"><span style="font-weight:200;">'
				.$pagination->total_results
				.'</span> results</span></h1><hr></div>';

			if ($pagination->total_results == 0 OR empty($pagination->total_results)) {
				$no_results = "<div class='col-xs-12 text-center'><h2 class='lead'>No results found.</h2></div>";
				echo $no_results;
			}

			foreach($result as $row)  {
				$ip = $row['addr'];
				$ip_url = "/node/{$ip}";
				$hostname = isset($row['hostname']) ? ' &nbsp;&nbsp;'.htmlentities($row['hostname']) : null;
				$last_seen = $row['last_seen'];
				$latency = !is_null($row['latency']) ? $row['latency'] : '?';
				$version = !is_null($row['version']) ? $row['version'] : '?';
				echo '    <div class="col-xs-12 col-md-8 col-md-offset-2 well search-result"><a href="'.$ip_url.'">'
					.'<p class="lead">'.$row['addr'].$hostname.'</p></a><hr><span class="search-result-hostname">'
					.'<span class="label label-default"><?=$latency?> ms</span>&nbsp;'
					.'<span class="label label-default">Protocol: <?=$protocol?></span></span>'
					.'<span class="search-result-lastseen">last seen: <time class="timeago" datetime="'.$last_seen.'">'
					.'</time></span></div>';
			}

			/* Show the paginationd links ( 1 2 3 4 5 6 7 ) etc. */
			echo $pagination->links_html;

		}
	}
}

$search = new search();

?>