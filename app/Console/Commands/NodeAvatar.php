<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Node;

class NodeAvatar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'node:avatar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates node avatars.';

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

        $this->info('Starting avatar generation');
        $nodes = \App\Node::whereNull('avatar_hash')->orderByRaw("RAND()")->take(10)->get();

        foreach ($nodes as $n) {
                $this->info('1');
            $hash_id = hash('sha256', $n->public_key);
                $this->info('3');
            $hash_url = public_path().'/assets/avatars/'.$hash_id.'.png';
                $this->info('4');
            $tile = new \Ranvis\Identicon\Tile();
                $this->info('5');
            $identicon = new \Ranvis\Identicon\Identicon(256, $tile);
                $this->info('6');
            if($identicon->draw($n->public_key.time())->save($hash_url)) {
                $this->info('7');
            $n->avatar_hash = $hash_id. '.png';
            $n->update();
                $this->info('9');
            $this->info('Generated avatar for '.$n->public_key);
                $this->info('10');
                usleep(10000);
            }
            else {
            $n->avatar_hash = 'avatar.png';
            $n->update();
            $this->error('Unable to generate avatar for '.$n->public_key);
            }
        }
    }
}