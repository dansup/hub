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

    public function makekeys($arg, $str) {

        $makekeys = './makekeys | head -n1';
        // bool test
        if ($arg === null) {
            if ( exec($makekeys) ) {
                return true;
            } else {
                return false;
            }
        }

        // work
        if ($str == 'makekeys') {
            return [
                'ipv6' => explode(" ", exec($makekeys,$Keys))[1],
                'pubKey' => explode(" ", exec($makekeys,$Keys))[2],
            ];
        } elseif ($str == 'random') {
            $faker = Faker::create();
            $addr = $faker->ipv6();
            $public_key = $faker->RandomElements([
                        'a','b','c','1','2','3', 'a','b','c','1','2','3',
                        'a','b','c','1','2','3', 'a','b','c','1','2','3',
                        'a','b','c','1','2','3', 'a','b','c','1','2','3',
                        'a','b','c','1','2','3', 'a','b','c','1','2','3',
                        'a','b','c','1','2','3', 'a','b','c','1','2','3',
                        ], $count = 53)[0] . ".k";
            return [ 'ipv6' => $addr, 'pubKey' => $public_key ];
        }
    }

    public function run()
    {
        Model::unguard();

        $faker = Faker::create();

        $totalNodes = 5001; // total nodes in 24 hour period
        $nmapper = 24;      // nmaps performed a day (1 * 24 hours)
        $node = 0;
        $rowidx = 0;

        $genKey='makekeys';
        if ($this->makekeys(null, $genKey)) {
            $genKey='makekeys';
        } else {
            $genKey='random';
        }
        printf("$this->logo makekeys util %s \n", $genKey);

        while ($rowidx <= $nmapper) {

          printf("\n$this->logo rowidx: %s", $rowidx);

          for ($node=0; $node <= $totalNodes; $node++) {

            $makekeys = $this->makeKeys(true, $genKey);
            $addr = $makekeys['ipv6'];
            $public_key = $makekeys['pubKey'];

            \DB::table('nodes')->insert(array (
              $rowidx =>
                [
                    'addr' => $addr,
                    'public_key' => $public_key,
                    'hostname' => $faker->colorName() . '-' . $faker->colorName() . '.hype',
                    'ownername' => $faker->firstName(),
                    'city' => $faker->city(),
                    'province' => $faker->cityPrefix(),
                    'country' => $faker->country(),
                    'version' => $faker->numberBetween(12,16),
                    'latency' => $faker->numberBetween(20,300),
                    'bio' => $faker->text(),
                    'privacy_level' => $faker->numberBetween(0,5),
                    'lat' => $faker->latitude(),
                    'lng' => $faker->longitude(),
                    'created_at' => $faker->date("Y-m-d H:i:s", $max = 'now'),
                    'updated_at' => $faker->date("Y-m-d H:i:s", $max = 'now'), // date('now')
                ]
            )); /* End of DB::table */
            $rowidx++;
          }
      }
    } /* ->run */
}