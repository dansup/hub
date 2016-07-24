<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Peer, App\Hub\Node\Node, App\Hub\Service, App\User;

class FixProfile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:profile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds missing profiles.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
     $q = \DB::table('nodes')
     ->select(\DB::raw('*'))
     ->join('profile', 'nodes.id', '!=', 'profile.node_id')
/*     ->where(\DB::raw('
        WHERE   NOT EXISTS
                (
                SELECT  1
                FROM    email e
                WHERE   e.id = m.id
                )
        '))*/
        ->take(10)
        ->get();  
        $this->info(count($q));
        return;
    }
}
