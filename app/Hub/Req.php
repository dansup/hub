<?php namespace App\Hub;

use Illuminate\Support\Facades\Request;

class Req extends Request {
    
    public static function ip($ip = false) {
        $ip = ($ip === false) ? Request::getClientIp() : $ip;
        $len = strlen($ip);
        $ip = (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) ? $ip : false;
        $ip = (substr($ip, 0, 2) === 'fc') ? $ip : false;
        if($ip === false) return false;
        if($len === 39) {
            return $ip;
        }
        else {
            $ip = explode(':', $ip);
            $res = '';
            $expand = true;
            foreach($ip as $seg)
            {
                if($seg == '' && $expand)
                {
                    // This will expand a compacted IPv6
                    $res .= str_pad('', (((8 - count($ip)) + 1) * 4), '0', STR_PAD_LEFT);
                    // Only expand once, otherwise it will cause troubles with ::1 or ffff::
                    $expand = false;
                }
                else
                {
                    // This will pad to ensure each IPv6 part has 4 digits.
                    $res .= str_pad($seg, 4, '0', STR_PAD_LEFT);
                }
            }
            return substr(chunk_split($res, 4, ':'), 0, -1);
        }
    }
}