<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CapiNmap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'capi:nmap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Periodically nmaps the network.';

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
        $nodes = \App\Node::orderByRaw("RAND()")->take(10)->get();
        foreach ($nodes as $n) {

            (new \App\Http\Controllers\NmapController())->scan($n->addr);
        }
        return true;
    }
}