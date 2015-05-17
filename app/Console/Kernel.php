<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		'App\Console\Commands\Inspire',
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$schedule->command('inspire')
				 ->hourly();

		$schedule->call(function() {
		    $pg = 0;
		    $api = new \App\Hub\Cjdns\Api;
		    $nodes = $api->call('NodeStore_dumpTable', ['page'=>$pg]);
		    $nmax = $nodes['count'] / 4;
		    for ($i=0; $i < $nmax; $i++) { 
		        $tnodes = $api->call('NodeStore_dumpTable', ['page' => $i]);
		        foreach($tnodes['routingTable'] as $n) {
		            $frag = explode('.', $n['addr']);
		            $ip = $n['ip'];
		            $node = \App\Node::firstOrNew([
		                'public_key' => $frag[5].'.k',
		                ]);
		            $node->addr = $n['ip'];
		            $node->public_key = $frag[5].'.k';
		            $node->version = substr($frag[0], 1);
		            $node->touch();
		            $node->save();

		        }
		    }
		    return true;
		})->everyTenMinutes();

		$schedule->call(function() { 
			    $nodes = \App\Node::orderByRaw("RAND()")->take(150)->get();
                foreach ($nodes as $n)
                    {
                        (new \App\Http\Controllers\PingController())->apiPing($n->addr);
                    }
                return true;
		})->everyFiveMinutes();
	}

}
