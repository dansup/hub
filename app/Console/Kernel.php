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
		'App\Console\Commands\CapiDump',
		'App\Console\Commands\CapiNmap',
		'App\Console\Commands\CapiPing',
		'App\Console\Commands\NodeAvatar',
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$schedule
		->command('capi:dump')
		->everyFiveMinutes()
		->withoutOverlapping();

		$schedule
		->command('capi:ping')
		->everyTenMinutes()
		->withoutOverlapping();

		/*
		 * Disabled until privacy concerns addressed
		 *

		$schedule
		->command('capi:nmap')
		->everyThirtyMinutes()
		->withoutOverlapping();
		*/

		/*
		 * Disabled until league/glide is added
		 *

		$schedule
		->command('node:avatar')
		->everyTenMinutes()
		->withoutOverlapping();
		*/

	}

}
