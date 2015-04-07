<?php
    /* 
      * Avatar Class
      */
namespace App\Models;

use PDO;
use \App\Models\Node;
use \Identicon\Identicon;
class Avatar {

    protected $db;

    function __construct()
    {
        $this->db = new \App\Models\Database();
    } 

    public function get($ip) {
        $db = $this->db;
        $dbh = $db->prepare('
            SELECT avatar_url
            FROM nodes
            WHERE addr = :addr;');
        $dbh->bindParam(':addr', $ip, PDO::PARAM_STR);
        if(!$dbh->execute()) {
            return false;
        }
        $avatar_url = $dbh->fetch(PDO::FETCH_COLUMN);
        return $avatar_url;
    }

    public function set($ip) {
        $db = $this->db;
        $identicon = new Identicon();
        $napi = new Node();
        $target = __DIR__.'/../public/assets/avatars/';
        $url = sha1($ip).'.png';
        $file_name = $target.$url;
        $image = $identicon->getImageData($ip, 512);
        file_put_contents($file_name, $image);
        $dbh = $db->prepare('
            UPDATE nodes
            SET avatar_url = :avatar_url
            WHERE addr = :addr;');
        $dbh->bindParam(':avatar_url', $url, PDO::PARAM_STR);
        $dbh->bindParam(':addr', $ip, PDO::PARAM_STR);
        if(!$dbh->execute()) {
            return false;
        }
        return true;

    }
    public function getMissingAvatars() {
        $db = $this->db;
        $dbh = $db->prepare('
            SELECT addr 
            FROM nodes 
            WHERE avatar_url IS NULL
            LIMIT 40;');
        if(!$dbh->execute()) {
            return false;
        }
        $res = $dbh->fetchAll(PDO::FETCH_COLUMN);
        return $res;
    }
}