<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Comment extends Model {

	protected $fillable = [
            'target',
            'type',
            'author_addr',
            'author_uid',
            'body'];

             //protected $primaryKey = 'target';

            public function getCreatedAtAttribute($date) {
                return Carbon::parse($date)->format('c');
            }
            public function getUpdatedAtAttribute($date) {
                return Carbon::parse($date)->format('c');
            }

            public function node() {
                return $this->belongsTo('App\Node', 'author_addr', 'addr');
            }

}
