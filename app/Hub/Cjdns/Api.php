<?php namespace App\Hub\Cjdns;

use App\Hub\Cjdns\Bencode;

class Api {
    
    public $bencode;
    public $buffersize = 69632;
    public $keepalive = 2;
    public $functions;
    private $socket;
    private $responses = [];

    function __construct() {
        $password = getenv('CJDNS_API_PASSWORD');
        $host = getenv('CJDNS_API_HOST');
        $port = getenv('CJDNS_API_PORT');
        $this->socket = stream_socket_client("udp://".$host.":".$port, $errorno, $errorstr);
        if(!$this->socket) {
            die("Failed to connect, Error #$errorno: $errorstr");
        }
        fwrite($this->socket, Bencode::encode(array("q"=>"ping")));
        $returndata = fread($this->socket, $this->buffersize);
        if(!ends_with($returndata, "1:q4:ponge")) {
            throw new \Exception('Cannot connect to cjdns admin api'. json_encode($returndata));
        }
        $this->password = $password;
    }

    function __destructor() {
        socket_close($this->socket);
    }

    public static function randStr($len = 5) {
            $bytes = openssl_random_pseudo_bytes($len, $cstrong);
            $hex   = bin2hex($bytes);
            while ($cstrong) {
                return $hex;
            }
    }

    function receive($txid) {
        while(!isset($this->responses[$txid])) {
            $data = fread($this->socket, $this->buffersize);
            if($data != "") {
                try {
                    $decoded = Bencode::decode($data, 'TYPE_ARRAY');
                    $this->responses[$decoded['txid']] = @$decoded;
                }
                catch(Exception $e) {
                    die("Failed to decode: ".$data);
                }
            }
        }
        $response = $this->responses[$txid];
        unset($this->response[$txid]);
        return $response;
    }

    public function send_raw($message) {
        $txid = $this->randStr();
        if(!isset($message['txid'])) {
            $message['txid'] = $txid;
        } else {
            $txid = $message['txid'];
        }
        fwrite($this->socket, Bencode::encode($message));
        return $txid;
    }

    private function getCookie() {
        $data = $this->receive($this->send_raw(["q" => "cookie"]));
        return $data['cookie'];
    }

    public function call($function, $args) {
        $cookie = $this->getCookie();
        $txid = $this->randStr();
        if ($this->password != NULL) {
            $request = [
                "q" => "auth",
                "aq" => $function,
                "hash" => hash("sha256", $this->password.$cookie),
                "cookie" => $cookie,
                "args" => $args,
                "txid" => $txid
            ];
        } else {
            $request = [
                "q" => $function,
                "args" => $args,
                "txid" => $txid
            ];
        }
        $requestBencoded = Bencode::encode($request);
        $request['hash'] = hash("sha256", $requestBencoded);
        $this->send_raw($request);
        return $this->receive($txid);
    }

}
