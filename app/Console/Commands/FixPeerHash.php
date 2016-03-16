<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Peer, App\Hub\Node\Node, App\Hub\Cjdns\KeyToIp;

class FixPeerHash extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:peerhash';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds missing hash(origin_ip,dest_publicKey) to peers table.';

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
        $limit = 100000;
        $this->info('Starting job, '.$limit.' jobs queued...');
        $peers = Peer::where('hash', '=', '')->take($limit)->get();
        foreach ($peers as $peer) {
            $peer->hash = hash('sha256', $peer->origin_ip.$peer->peer_key);
            $peer->timestamps = false;
            $peer->save();
        }
        $this->info('Completed job.');
    }
}
