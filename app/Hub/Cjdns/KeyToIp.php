<?php

namespace App\Hub\Cjdns;

class KeyToIp {

  public static function base32Decode($input)
  {
    $output = array(strlen($input));
    $numForAscii = [
        99,99,99,99,99,99,99,99,99,99,99,99,99,99,99,99,
        99,99,99,99,99,99,99,99,99,99,99,99,99,99,99,99,
        99,99,99,99,99,99,99,99,99,99,99,99,99,99,99,99,
         0, 1, 2, 3, 4, 5, 6, 7, 8, 9,99,99,99,99,99,99,
        99,99,10,11,12,99,13,14,15,99,16,17,18,19,20,99,
        21,22,23,24,25,26,27,28,29,30,31,99,99,99,99,99,
        99,99,10,11,12,99,13,14,15,99,16,17,18,19,20,99,
        21,22,23,24,25,26,27,28,29,30,31,99,99,99,99,99
        ];
    $outputIndex = 0;
    $inputIndex = 0;
    $nextByte = 0;
    $bits = 0;

    while ($inputIndex < strlen($input)) {
        $o = ord($input[$inputIndex]);
        if ($o & 0x80) {
          return false;
        }
        $b = $numForAscii[$o];
        $inputIndex += 1;
        if ($b > 31) {
          return false;
          //raise ValueError("bad character " + input[inputIndex]);
        } 

        $nextByte |= ($b << $bits);
        $bits += 5;

        if ($bits >= 8) {
            $output[$outputIndex] = $nextByte & 0xff;
            $outputIndex += 1;
            $bits -= 8;
            $nextByte >> 8;
        }
    }
    if ($bits = 5 or $nextByte) {
        return false; 
        //raise ValueError("bits is " + str(bits) + " and nextByte is " + str(nextByte));
    }

    return $output;
  }
  public static function convert($publicKey)
  {
    $publicKey = filter_var($publicKey);
    if(strlen($publicKey) !== 54) {
      return false;
    }
    $ip = exec('/var/abs/local/yaourtbuild/cjdns-git/src/cjdns/publictoip6 '.$publicKey);

    return $ip;
  }
  public static function convertDeprecated($publicKey)
  {
    if( ends_with($publicKey, '.k') == false ) {
      return false;
    }
    $keyBytes = self::base32Decode( substr($publicKey, 0, -2) );
    $hashOnce = hash('sha512', $keyBytes[0], true);
    $hashTwice = hash('sha512', $hashOnce,true);
    $hashTwice = bin2hex($hashTwice);
    $s = '';
    $i = 0;
    foreach (range(0,32,4) as $range) {
      if($i > 7) { continue; }
      if($i ==  7) {
        $s .= $hashTwice[$range].$hashTwice[$range+1].$hashTwice[$range+2].$hashTwice[$range+3];
      } else {
        $s .= $hashTwice[$range].$hashTwice[$range+1].$hashTwice[$range+2].$hashTwice[$range+3].':';
      }
      $i++;
    }
    return $s;
  }

}