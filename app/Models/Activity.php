<?php
namespace App\Models;

use PDO;

class Activity {

    protected $db;

    function __construct() {
        $this->db = new \App\Models\Database();
    }

    public function newAction(
        $type, 
        $action_author, 
        $author_type, 
        $action_identifier, 
        $identifier_type, 
        $action_title, 
        $action_body = null, 
        $action_meta = null
        ) {

        $a_types = [
        'node_profile_update',
        'new_comment',
        'new_comment_like',
        'new_comment_reply',
        'node_comment',
        'node_comment_reply',
        'node_comment_like',
        'created_meshlocal',
        'meshlocal_profile_update',
        'meshlocal_comment'
        ];
        if(!in_array($type, $a_types))
        {
            return false;
        }

        $action_meta = (is_null($action_meta)) ? null : json_encode($action_meta);
        $dbh = $this->db->prepare(
            'INSERT into activity_log
            (timestamp, type, author, author_type, identifier, identifier_type, title, body, meta)
            VALUES(:ts, :type, :act_author, :author_type, :act_id, :act_type, :act_title, :act_body, :act_meta)');
        $dbh->bindParam(':ts', date('c'));
        $dbh->bindParam(':type', $type);
        $dbh->bindParam(':act_author', $action_author);
        $dbh->bindParam(':author_type', $author_type);
        $dbh->bindParam(':act_id', $action_identifier);
        $dbh->bindParam(':act_type', $identifier_type);
        $dbh->bindParam(':act_title', htmlentities($action_title));
        $dbh->bindParam(':act_body', htmlentities($action_body));
        $dbh->bindParam(':act_meta', $action_meta);
        if(!$dbh->execute()) {
            return $dbh->errorInfo();
        }
        return true;
    }

    public function getFeed($type, $identifier, $page = 1) {
        $types = ['node', 'node_comment', 'meshlocal_comment', 'service_comment'];
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
                $pagination = new \App\Utils\Pagination($page, 
                    'SELECT a.* FROM activity_log a
                    WHERE a.author = :dest 
                    AND a.type = :type
                    ORDER BY a.id desc'
                    , $options);  
            }
            catch(paginationException $e)
            {
                return $e;
            }

            $pagination->bindParam(':dest', $identifier, PDO::PARAM_STR);  
            $pagination->bindParam(':type', $type, PDO::PARAM_STR);  

            $pagination->execute();

            if($pagination->success === true)  
            {  

                $result = $pagination->resultset->fetchAll();
                $resp = [];

                $resp['rc'] = 500;
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

}
