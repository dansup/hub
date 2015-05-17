<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model {

        protected $table = 'services';
        protected $fillable = [
        'name', 
        'addr',
        'bio',
        'city',
        'country'];

        public function node() {
        return $this->belongsTo('Node');
        }
}
