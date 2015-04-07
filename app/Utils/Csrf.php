<?php
namespace App\Utils;

class Csrf {
    public function get_token_id() {
        if(isset($_SESSION['token_id'])) { 
                return $_SESSION['token_id'];
        } else {
                $token_id = $this->random(20);
                $_SESSION['token_id'] = $token_id;
                return $token_id;
        }
}
public function get_token() {
        if(isset($_SESSION['token_value'])) {
                return $_SESSION['token_value']; 
        } else {
                $token = $this->random(100);
                $_SESSION['token_value'] = $token;
                return $token;
        }
 
}
public function check_valid($method) {
        if($method == 'post' || $method == 'get') {
                $post = $_POST;
                $get = $_GET;
                if(isset(${$method}[$this->get_token_id()]) && (${$method}[$this->get_token_id()] == $this->get_token())) {
                        unset($_SESSION['token_id'],$_SESSION['token_value'],$_SESSION['identifier'],$_SESSION['token']);
                        return true;
                } else {
                        return false;        
                }
        } else {
                return false;        
        }
}
public function form_names($names, $regenerate) {
 
        $values = array();
        foreach ($names as $n) {
                if($regenerate == true) {
                        unset($_SESSION[$n]);
                }
                $s = isset($_SESSION[$n]) ? $_SESSION[$n] : $this->random(20);
                $_SESSION[$n] = $s;
                $values[$n] = $s;        
        }
        return $values;
}
private function random($len) {

        $bytes = openssl_random_pseudo_bytes($len, $cstrong);
        $hex   = bin2hex($bytes);
        while ($cstrong) {
            return $hex;
        }
}

}
