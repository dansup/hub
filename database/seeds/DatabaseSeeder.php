<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Faker\Factory as Faker;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/*use Jenssegers\Mongodb\Model as Eloquent;*/

/*use Monolog\Handler\StreamHandler;*/
/*use Monolog\Logger;*/


class DatabaseSeeder extends Seeder {

    public function __construct() {
        $this->logo = "🙀\t";
    }

    public function run()
    {
        factory('App\Node', 100)->create()->each(function($n) {
            $n->save(factory('App\Node')->make());
        });
    } /* ->run */
}