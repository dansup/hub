<?php
/**
*  People Class
*/
namespace App\Models;

use PDO;
use \App\Utils\Pagination;

class People  {

    protected $db;
    function __construct()
    {
        $this->db = new \App\Models\Database();
    }
    public function usernameExists($id) {
            $permitted_chars = ["_"];
            $username = strtolower(trim($id));
            $username = (mb_strlen($username) > 3 && mb_strlen($username) < 14) ? $username : false;
            $username = (ctype_alnum( str_replace($permitted_chars, '', $username ) )) ? $username : false;
            $dbh = $this->db->prepare('SELECT user_id FROM users WHERE username = :username');
            $dbh->bindParam(':username', $username);
            if(!$dbh->execute()) {
                return false;
            }
            $res = $dbh->fetch(PDO::FETCH_COLUMN);
            if($res) {
                return true;
            }
            else {
                return false;
            }
    }
    public function getAll($page, $orderby) {
            $options = array(
                'results_per_page'              => 10,
                'url'                           => "?page=*VAR*&ob={$orderby}",
                'db_handle'                     => $this->db,
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
                $order_by = 'date_created ASC';
                break;
                case 2:
                $order_by = 'username ASC';
                break;
                default:
                $order_by = 'date_created DESC';
                break;
            }

            try
            {
                $paginate = new \App\Utils\Pagination($page, 
                    "SELECT
                        user_id, 
                        date_created,
                        username
                    FROM users  
                    ORDER BY {$order_by}"
                    ,$options);

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
                    $resp['data'] = $result;
                    $resp['pagination'] = ($result >10 ) ? null : $paginate->links_html;

                    return $resp;
                }
                else {
                    return false;
                }
            } /* /if $paginate-> */
        }

        public function getProfile($id) {
            return $id;
        }
}
