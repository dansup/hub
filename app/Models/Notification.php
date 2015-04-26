<?php
/**
*  Notification Class
*/
namespace App\Models;

use \App\Config\App as Config;
use PDO;

class Notification  {

    protected $db;

    function __construct()
    {
        $this->db = new \App\Models\Database();
    }
    public function get($user_id) {

        $dbh = $this->db->prepare('
            SELECT *
            FROM activitylog
            WHERE user = :uid
            ORDER BY date DESC
            LIMIT 10');
        $dbh->bindParam(':uid', $user_id);
        while($dbh->execute) {
            return $dbh->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    public function create($user, $sender, $type) {
        switch ($type) {
            case 'node_comment':
                $msg = "{$sender} left a comment on your page.";
                $action = "/node/{$user}";
                break;
            
            default:
                return false;
                break;
        }

        $timestamp = date('c');
        $dbh = $this->db->prepare('
            INSERT INTO notify(user, sender, title, type, timestamp, action) 
            VALUES (:user, :sender, :title, :type, :timestamp, :action);
            ');
        $dbh->bindParam(':user', $user);
        $dbh->bindParam(':sender', $sender);
        $dbh->bindParam(':title', $msg);
        $dbh->bindParam(':type', $type);
        $dbh->bindParam(':timestamp', $timestamp);
        $dbh->bindParam(':action', $action);
        if(!$dbh->execute()) {
            return $dbh->errorInfo();
        }
        return true;
    }
}