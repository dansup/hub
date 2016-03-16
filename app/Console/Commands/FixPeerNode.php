<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Peer, App\Hub\Node\Node, App\Hub\Cjdns\KeyToIp;

class FixPeerNode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:peernode';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds missing nodes from peer records';

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
        $limit = 500000;
        $i = 0;
        if ($this->confirm('Do you wish to continue? [y|N]')) {
            $nodes = Peer::selectRaw('DISTINCT peer_key')->orderByRaw('RAND()')->take($limit)->get();
            foreach ($nodes as $node) {
                $count = Node::wherePublicKey($node->peer_key)->count();
                if($count == 0) {
                    $n = new Node;
                    $n->public_key = $node->peer_key;
                    $n->addr = KeyToIp::convert($node->peer_key);
                    $n->save();
                    $i++;
                    $this->info('Fixed '.$node->peer_key);
                }
            }
            $this->info('Fixed '.$i.' out of '.$limit.' records.');
        }
    }
}
