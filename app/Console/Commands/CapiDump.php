<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Capi;

class CapiDump extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'capi:dump';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dumps and stores the cjdns routing table to discover and update node information.';

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
        $pg = 0;
        $api = new Capi;
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
    }
}
