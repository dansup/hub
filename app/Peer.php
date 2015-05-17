<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Peer extends Model {

	protected $table = 'peers';
      protected $fillable = ['origin_ip', 'peer_key', 'protocol', 'monitor_ip'];

      protected $hidden = [
      'id',
      'updated_at'
      ];

      protected $primaryKey = 'origin_ip';

      public function node() {
            return $this->belongsTo('App\Node', 'peer_key', 'public_key');
      }

}
