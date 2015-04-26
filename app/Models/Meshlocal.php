<?php
/**
*  MeshLocal Class
*/
namespace App\Models;

use PDO;
use \App\Utils\Pagination as Pagination;

class Meshlocal  {

    protected $db;

    function __construct()
    {
        $this->db = new \App\Models\Database();
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
            $order_by = isset($orderby) ? $orderby : 'created DESC';
            switch ($order_by) {
                case 1:
                $order_by = 'name ASC';
                break;
                case 2:
                $order_by = 'created ASC';
                break;
                default:
                $order_by = 'created DESC';
                break;
            }

            try
            {
                $paginate = new \App\Utils\Pagination($page, 
                    "SELECT *
                    FROM meshlocals  
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
                    $resp['pagination'] = ($result < 10) ? $paginate->links_html : null;

                    return $resp;
                }
                else {
                    return false;
                }
            } /* /if $paginate-> */
        }
    public function createNew($name, $location, $bio, $admin_ip) {
        $url_slug = str_replace('+', '-', urlencode(htmlentities(strtolower($name))));
        $dbh = $this->db->prepare('INSERT into meshlocals (name, created, city, state, country, lat, lng, bio, admin_ip, url_slug)
            VALUES(:name, :created, :city, :state, :country, :lat, :lng, :bio, :admin_ip, :url_slug)');
        $dbh->bindParam(':name', $name, PDO::PARAM_STR);
        $dbh->bindParam(':created', date('c'), PDO::PARAM_STR);
        $dbh->bindParam(':city', $location['city'], PDO::PARAM_STR);
        $dbh->bindParam(':state', $location['state'], PDO::PARAM_STR);
        $dbh->bindParam(':country', $location['country'], PDO::PARAM_STR);
        $dbh->bindParam(':lat', $location['lat'], PDO::PARAM_STR);
        $dbh->bindParam(':lng', $location['lng'], PDO::PARAM_STR);
        $dbh->bindParam(':bio', $bio, PDO::PARAM_STR);
        $dbh->bindParam(':admin_ip', $admin_ip, PDO::PARAM_STR);
        $dbh->bindParam(':url_slug', $url_slug, PDO::PARAM_STR);
        if(!$dbh->execute()) {
            return var_dump($dbh->errorInfo());
        }
        $url = '/meshlocal/'.$this->db->lastInsertId().'/'.$url_slug;
        return $url;

    }

    public function getProfile($id, $slug) {
         $dbh = $this->db->prepare(
            'SELECT *
            FROM meshlocals
            WHERE id = :id
            AND url_slug = :url_slug;');
         $dbh->bindParam(':id', $id, PDO::PARAM_INT);
         $dbh->bindParam(':url_slug', $slug, PDO::PARAM_STR);
         if(!$dbh->execute()) {
            return false;
         }
         return $dbh->fetch(PDO::FETCH_ASSOC);
    }
    public function redirectId($id) {
         $dbh = $this->db->prepare(
            'SELECT url_slug
            FROM meshlocals
            WHERE id = :id;');
         $dbh->bindParam(':id', $id, PDO::PARAM_INT);
         if(!$dbh->execute()) {
            return false;
         }
         return $dbh->fetch(PDO::FETCH_COLUMN);
    }
}