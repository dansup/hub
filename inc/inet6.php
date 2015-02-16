<?php

/**
 * IPv6 Address Functions for PHP
 *
 * Functions to manipulate IPv6 addresses for PHP
 *
 * Copyright (C) 2009, 2011 Ray Patrick Soucy
 *
 * LICENSE:
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package   inet6
 * @author    Ray Soucy <rps@soucy.org>
 * @version   1.0.2
 * @copyright 2009, 2011 Ray Patrick Soucy 
 * @link      http://www.soucy.org/
 * @license   GNU General Public License version 3 or later
 * @since     File available since Release 1.0.1
 */

 /**
  * Expand an IPv6 Address
  *
  * This will take an IPv6 address written in short form and expand it to include all zeros. 
  *
  * @param  string  $addr A valid IPv6 address
  * @return string  The expanded notation IPv6 address
  */

class inet6 {

      public function inet6_expand($addr)
      {
          /* Check if there are segments missing, insert if necessary */
          if (strpos($addr, '::') !== false) {
              $part = explode('::', $addr);
              $part[0] = explode(':', $part[0]);
              $part[1] = explode(':', $part[1]);
              $missing = array();
              for ($i = 0; $i < (8 - (count($part[0]) + count($part[1]))); $i++)
                  array_push($missing, '0000');
              $missing = array_merge($part[0], $missing);
              $part = array_merge($missing, $part[1]);
          } else {
              $part = explode(":", $addr);
          } // if .. else
          /* Pad each segment until it has 4 digits */
          foreach ($part as &$p) {
              while (strlen($p) < 4) $p = '0' . $p;
          } // foreach
          unset($p);
          /* Join segments */
          $result = implode(':', $part);
          /* Quick check to make sure the length is as expected */ 
          if (strlen($result) == 39) {
              return $result;
          } else {
              return false;
          } // if .. else
      } // inet6_expand

       /**
        * Compress an IPv6 Address
        *
        * This will take an IPv6 address and rewrite it in short form. 
        *
        * @param  string  $addr A valid IPv6 address
        * @return string  The address in short form notation
        */
      function inet6_compress($addr)
      {
          /* PHP provides a shortcut for this operation */
          $result = inet_ntop(inet_pton($addr));
          return $result;
      } // inet6_compress

       /**
        * Generate an IPv6 mask from prefix notation
        *
        * This will convert a prefix to an IPv6 address mask (used for IPv6 math) 
        *
        * @param  integer $prefix The prefix size, an integer between 1 and 127 (inclusive)
        * @return string  The IPv6 mask address for the prefix size
        */
      function inet6_prefix_to_mask($prefix)
      {
          /* Make sure the prefix is a number between 1 and 127 (inclusive) */
          $prefix = intval($prefix);
          if ($prefix < 0 || $prefix > 128) return false;
          $mask = '0b';
          for ($i = 0; $i < $prefix; $i++) $mask .= '1';
          for ($i = strlen($mask) - 2; $i < 128; $i++) $mask .= '0';
          $mask = gmp_strval(gmp_init($mask), 16);
          for ($i = 0; $i < 8; $i++) {
              $result .= substr($mask, $i * 4, 4);
              if ($i != 7) $result .= ':';
          } // for
          return inet6_compress($result);
      } // inet6_prefix_to_mask

       /**
        * Convert an IPv6 address and prefix size to an address range for the network.
        *
        * This will take an IPv6 address and prefix and return the first and last address available for the network. 
        *
        * @param  string  $addr A valid IPv6 address
        * @param  integer $prefix The prefix size, an integer between 1 and 127 (inclusive)
        * @return array   An array with two strings containing the start and end address for the IPv6 network
        */
      function inet6_to_range($addr, $prefix)
      {
          $size = 128 - $prefix;
          $addr = gmp_init('0x' . str_replace(':', '', inet6_expand($addr)));
          $mask = gmp_init('0x' . str_replace(':', '', inet6_expand(inet6_prefix_to_mask($prefix))));
          $prefix = gmp_and($addr, $mask);
          $start = gmp_strval(gmp_add($prefix, '0x1'), 16);
          $end = '0b';
          for ($i = 0; $i < $size; $i++) $end .= '1';
          $end = gmp_strval(gmp_add($prefix, gmp_init($end)), 16);
          for ($i = 0; $i < 8; $i++) {
              $start_result .= substr($start, $i * 4, 4);
              if ($i != 7) $start_result .= ':';
          } // for
          for ($i = 0; $i < 8; $i++) {
              $end_result .= substr($end, $i * 4, 4);
              if ($i != 7) $end_result .= ':';
          } // for
          $result = array(inet6_compress($start_result), inet6_compress($end_result));
          return $result;
      } // inet6_to_range

       /**
        * Convert an IPv6 address to two 64-bit integers.
        *
        * This will translate an IPv6 address into two 64-bit integer values for storage in an SQL database. 
        *
        * @param  string  $addr A valid IPv6 address
        * @return array   An array with two strings containing the 64-bit interger values
        */
      function inet6_to_int64($addr)
      {
          /* Expand the address if necessary */
          if (strlen($addr) != 39) {
              $addr = inet6_expand($addr);
              if ($addr == false) return false;
          } // if
          $addr = str_replace(':', '', $addr);
          $p1 = '0x' . substr($addr, 0, 16);
          $p2 = '0x' . substr($addr, 16);
          $p1 = gmp_init($p1);
          $p2 = gmp_init($p2);
          $result = array(gmp_strval($p1), gmp_strval($p2));
          return $result;
      } // inet6_to_int64()

       /**
        * Convert two 64-bit integer values into an IPv6 address
        *
        * This will translate an array of 64-bit integer values back into an IPv6 address 
        *
        * @param  array  $val An array containing two strings representing 64-bit integer values
        * @return string An IPv6 address
        */
      function int64_to_inet6($val)
      {
          /* Make sure input is an array with 2 numerical strings */
          $result = false;
          if ( ! is_array($val) || count($val) != 2) return $result;
          $p1 = gmp_strval(gmp_init($val[0]), 16);
          $p2 = gmp_strval(gmp_init($val[1]), 16);
          while (strlen($p1) < 16) $p1 = '0' . $p1;
          while (strlen($p2) < 16) $p2 = '0' . $p2;
          $addr = $p1 . $p2;
          for ($i = 0; $i < 8; $i++) {
              $result .= substr($addr, $i * 4, 4);
              if ($i != 7) $result .= ':';
          } // for
          return inet6_compress($result);
      } // int64_to_inet6()

      // trailing PHP tag omitted to prevent accidental whitespace
}
$inet6 = new inet6();