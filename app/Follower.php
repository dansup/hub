<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model {

            protected $table = 'followers';

            protected $fillable = [
            'target', 
            'target_type', 
            'follower_addr', ];

            protected $hidden = [
            'id', 
            'created_at',
            'updated_at',
            'target_type', 
            'target'];


}
