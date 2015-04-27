<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Node extends Model {

            /**
             * The database table used by the model.
             *
             * @var string
             */
            protected $table = 'nodes';

            public $timestamps = false;

            protected $fillable = ['public_key'];

            /**
             * The attributes excluded from the model's JSON form.
             *
             * @var array
             */
            //protected $hidden = ['password', 'remember_token'];

            protected $dates = ['first_seen', 'last_seen'];


            protected function getDateFormat() {
                return 'Y-m-d\TH:i:sO';
            }
            public function setFirstSeenFormattedAttribute($date) {
                return Carbon::parse($date)->format('c');
            }

            public function setLastSeenFormattedAttribute($date) {
                return Carbon::parse($date)->format('c');
            }
            public function getFirstSeenFormattedAttribute($date) {
                return Carbon::parse($date)->format('c');
            }

            public function getLastSeenFormattedAttribute($date) {
                return Carbon::parse($date)->format('c');
            }
}
