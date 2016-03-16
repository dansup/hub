<?php 

namespace App\Hub\Node;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Request;
use App\Hub\Cjdns\Api;

class Node extends Model {

    /* use SoftDeletes; */
    
   /**
    * The database table used by the model.
    *
    * @var string
    */

    protected $table = 'nodes';

    protected $fillable = [
        'hostname', 'ownername', 'city',
        'province', 'country', 'lat', 'lng',
    ];

    /*
    TBD: Guarded or Ungarded
            'addr',
            'public_key',
    */
    protected $guarded = [
        'addr',
        'public_key'
    ];

    /**
    * The attributes excluded from the model's JSON form.
    *
    * @var array
    */
    protected $hidden = [ 
    'id',
    'privacy_level',
    'abuse',
    'lat',
    'lng',
    'active',
    'peers',
    'badges',
    'activity',
    'services',
    'follows',
    'followers',
    'comments',
    'deleted_at',
    'activity_count',
    'city',
    'province',
    'pings',
    'pingGraph',
    'avatar_hash',
    'verified',
    ];

    protected $dates = [ 'created_at', 'deleted_at', 'updated_at' ];

    public function getHostnameAttribute($value) {
        return (empty($value)) ? NULL : $value;
    }
    public function buildNodeUrl($use_publicKey = false, $auto = false)
    {
        if($auto) {
            if($this == null) {
                return false;
            }
            $use_publicKey = empty($this->addr) ? true : false;
        }
        if($use_publicKey) {
          $url = env('APP_URL').'/node/pubkey/'.$this->public_key;
        } else {
          $url = env('APP_URL').'/node/ip/'.$this->addr;
        }
      return $url;
    }
    public static function age($node, $append = false)
    {
        $age = $node->created_at->diffInMonths();
        $unit = ' months';
        if ( $age > 12 ) {
            $age = $node->created_at->diffInYears();
            $unit = ($age == 1) ? ' year' : ' years';
        }
        $unit = ($append == false) ? null : $unit;
        return $age.$unit;
    }

    public function comments()
    {
      return $this->morphMany('App\Comment', 'commentable');
    }

    public function peers()
    {
        return $this->hasMany('App\Peer', 'origin_ip', 'addr');
    }
}
