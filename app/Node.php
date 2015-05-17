<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Request;
use App\Hub\Cjdns\Api;

class Node extends Model {

    //use SoftDeletes;
            /**
             * The database table used by the model.
             *
             * @var string
             */
            protected $table = 'nodes';

            protected $primaryKey = 'public_key';

            protected $fillable = [
            'hostname', 
            'ownername', 
            'city', 
            'province', 
            'country', 
            'lat', 
            'lng',
            ];

            protected $guarded = [
            /* TBD: Guarded or Ungarded 
            'addr',
            'public_key',*/
            ];

            /**
             * The attributes excluded from the model's JSON form.
             *
             * @var array
             */
            protected $hidden = [
            'privacy_level',
            ];

            protected $dates = [
            'first_seen', 
            'last_seen',
            'deleted_at',
            ];



            public function getCreatedAtAttribute($value) {

                return \Carbon\Carbon::parse($value)->toIso8601String();
            }

            public function getUpdatedAtAttribute($value) {

                return \Carbon\Carbon::parse($value)->toIso8601String();
            }

            public function comments() {
                return $this->hasMany('App\Comment', 'target', 'addr');
            }

            public function last3Comments() {
                return $this->hasMany('App\Comment', 'target', 'addr')->orderBy('id', 'DESC')->limit(3);
            }

            public function last5Comments() {
                return $this->hasMany('App\Comment', 'target', 'addr')->orderBy('id', 'DESC')->limit(5);
            }

            public function peers() {
                return $this->hasMany('App\Peer', 'origin_ip', 'addr');
            }

            public function services() {
                return $this->hasMany('App\Service', 'addr');
            }

            public function follows() {
                return $this->hasMany('App\Follower', 'follower_addr', 'addr');
            }

            public function followers() {
                return $this->hasMany('App\Follower', 'target', 'addr');
            }

            public function activity() {
                return $this->hasMany('App\Activity', 'actor_user_id', 'addr');
            }
            
            public function last5Activity() {
                return $this->hasMany('App\Activity', 'actor_user_id', 'addr')->orderBy('id', 'DESC')->limit(5);
            }

            // DEPRECIATED
            public function ping($timeout = 200) {
                return (new Api())->call(
                    "RouterModule_pingNode",[
                    "path" => $this->primaryKey,
                    "timeout" => $timeout
                    ]);
            }

            /**
             * DEPRECIATED!
             * Validate and pad cjdns ipv6 address
             *
             * @return string
             **/
            public static function ip($ip = false)
            {
                $ip = ($ip === false) ? Request::getClientIp() : $ip;
                $len = strlen($ip);
                $ip = (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) ? $ip : false;
                $ip = (substr($ip, 0, 1) === 'fc') ? $ip : false;
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
