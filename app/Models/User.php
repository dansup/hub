<?php
/* 
* User Class
*/
namespace App\Models;

use PDO;

class User {

    protected $db;
    private $app;

    function __construct()
    {
        $this->db = new \App\Models\Database();
        $this->app = \Slim\Slim::getInstance();
    }
    public function fingerprint() {
        // Secure session handling. 
        $req = $this->app->request;
        return hash('sha512', $req->getIp().$req->getUserAgent());
    }
    public function pw($password) {
        $pw_opts = [
            'cost' => 12,
        ];
        return password_hash($password, PASSWORD_BCRYPT, $pw_opts);
    }
    public function auth($level = 1) {
        $sid = (isset($_SESSION['id']) && !empty($_SESSION['id'])) ? filter_var($_SESSION['id']) : false;
        $skey = (isset($_SESSION['key']) && !empty($_SESSION['key'])) ? filter_var($_SESSION['key']) : false;

        if(!$sid OR !$skey) {
            return false;
        }

        if($level === 1) {
            $session = (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) ? true : false;
            $since = (isset($_SESSION['session_since']) && is_int($_SESSION['session_since']) ) ? $_SESSION['session_since'] : false;
            $session = ($since !== false && ($since - time()) < getenv('SESSION_TIMEOUT') ) ? true : false;
            return $session;
        }
        if($level === 2) {
            $dbh = $this->db->prepare(
                'SELECT
                count(id),
                UNIX_TIMESTAMP(NOW()) - unix_timestamp(updated) < :timeout as sessiontime,
                user_id
                FROM logged_in
                WHERE id = :id AND 
                fingerprint = :fi AND 
                ip = :ip AND
                user_key = :key');
            $dbh->bindParam(':timeout', getenv('SESSION_TIMEOUT'));
            $dbh->bindParam(':id', $sid);
            $dbh->bindParam(':fi', $this->fingerprint());
            $dbh->bindParam(':ip', $this->app->request->getIp());
            $dbh->bindParam(':key', $skey);
            if(!$dbh->execute()) {
                return ['e'=>'inv dbq'];
            }
            $resp = $dbh->fetch(PDO::FETCH_ASSOC);
            if($resp['count(id)'] !== 1) {
                return false;
            }
            return true;
        }
    }
    public function createNew($username, $email, $password) {

        $password = $this->pw($password);
        $ts = date('c');

        $dbh = $this->db->prepare(
            'INSERT into users 
            (username, email, password, date_created, date_modified)
            VALUES(:us, :em, :pw, :dc, :dm);');
        $dbh->bindParam(':us', $username);
        $dbh->bindParam(':em', $email);
        $dbh->bindParam(':pw', $password);
        $dbh->bindParam(':dc', $ts);
        $dbh->bindParam(':dm', $ts);
        if(!$dbh->execute()) {
            return false;
        }

        return $this->db->lastInsertId();

    }
    public function login($username, $password, $ip) {

        $dbh = $this->db->prepare(
            'SELECT user_id,
            password
            FROM users
            WHERE username = :username
            AND NOT status = 0;');
        $dbh->bindParam(':username', $username);
        if(!$dbh->execute()) {
            return false;
        }
        $u = $dbh->fetch(PDO::FETCH_ASSOC);
        if($u !== false OR $u != null) {
            $valid_password = password_verify($password, $u['password']);
            if($valid_password == true) {
                $session_hash = bin2hex(openssl_random_pseudo_bytes(28));
                $dbh = $this->db->prepare(
                    'INSERT into logged_in
                    (created, updated, ip, user_key, fingerprint, user_id)
                    VALUES(:ts, :ts, :ip, :uk, :f, :uid);');
                $dbh->bindParam(':ts', date('c'));
                $dbh->bindParam(':ip', $ip);
                $dbh->bindParam(':uk', $session_hash);
                $dbh->bindParam(':f', $this->fingerprint());
                $dbh->bindParam(':uid', $u['user_id']);
                if(!$dbh->execute()) {
                    return false;
                }
                session_regenerate_id(true);
                $_SESSION['id'] = $this->db->lastInsertId();
                $_SESSION['key'] = $session_hash;
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = (int) $u['user_id'];
                $_SESSION['session_since'] = time();
                $_SESSION['username'] = $username;
                $_SESSION['last_comment'] = false;
                $_SESSION['comment_count'] = 0;
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }
    public function oauthLogin($username, $ip) {
        $dbh = $this->db->prepare(
            'SELECT user_id 
            FROM users 
            WHERE username = :username ;');
        $dbh->bindParam(':username', $username);
        if(!$dbh->execute()) {
            return false;
        }
        $uid = (int) $dbh->fetch(PDO::FETCH_COLUMN);
        if($uid == null OR !is_int($uid)) {
            return false;
        }
        $session_hash = bin2hex(openssl_random_pseudo_bytes(28));
        $dbh = $this->db->prepare(
            'INSERT into logged_in
            (created, updated, ip, user_key, fingerprint, user_id)
            VALUES(:ts, :ts, :ip, :uk, :f, :uid);');
        $dbh->bindParam(':ts', date('c'));
        $dbh->bindParam(':ip', $ip);
        $dbh->bindParam(':uk', $session_hash);
        $dbh->bindParam(':f', $this->fingerprint());
        $dbh->bindParam(':uid', $uid);
        if(!$dbh->execute()) {
            return false;
        }
        session_regenerate_id(true);
        $_SESSION['id'] = $this->db->lastInsertId();
        $_SESSION['key'] = $session_hash;
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = (int) $uid;
        $_SESSION['session_since'] = time();
        $_SESSION['username'] = $username;
        $_SESSION['last_comment'] = false;
        $_SESSION['comment_count'] = 0;
        return true;
    }
    public function getUserId() {
        $uid = ( isset($_SESSION['user_id']) && is_int($_SESSION['user_id']) ) ? $_SESSION['user_id'] : false;
        return $uid;
    }
        public function getUsername() {
        $u = ( isset($_SESSION['username']) && is_string($_SESSION['username']) ) ? $_SESSION['username'] : false;
        return $u;
    }

}