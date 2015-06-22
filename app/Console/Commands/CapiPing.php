<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Node;
use App\Http\Controllers\PingController;

class CapiPing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'capi:ping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ping a few random nodes.';

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
        $nodes = Node::orderByRaw("RAND()")->take(50)->get();
        foreach ($nodes as $n) {

            (new PingController())->apiPing($n->addr);

        }
        return true;
    }
}
