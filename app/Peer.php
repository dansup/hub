<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Peer extends Model
{
    public function peerNode()
    {
      return $this->hasOne('App\Hub\Node\Node', 'public_key', 'peer_key');
    }
}
