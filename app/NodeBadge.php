<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NodeBadge extends Model
{
    protected $table = "node_badge";

    public function node() {
      return $this->belongsTo('App\Node', 'public_key');
    }
}
