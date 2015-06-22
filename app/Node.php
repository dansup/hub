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


}
