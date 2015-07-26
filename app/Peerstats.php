<?php namespace App;

use Log;
use Input;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Moloquent;

class Peerstats extends Moloquent {

    protected $connection = 'mongo';
    protected $collection = 'peerstats';

    function __construct($node = null) {
        $this->node = $node;
    }

    public function PeersUpdate() {

        /* Logging */
        $LogInfo = [ "ip" => Input::ip(), "peerstats" => count($this->peerstats) ];
        $log = new Logger('Peerstats');
        $log->pushHandler(new StreamHandler('Peerstats.log', Logger::NOTICE));

        $log->addNotice('PeersUpdate()', $LogInfo);

        Log::useFiles('php://stdout', 'info');
        Log::info(json_encode($LogInfo));

        /* DB Update */
        $Cfg = $this;
        $Cfg->save();
        return($Cfg);

    }

    public function Peerstats_SelectCollection($collection = null, $dbconnection = null) {
        $collection = (!$collection) ? $this->collection : $collection;
        $dbconnection = (!$dbconnection) ? $this->dbconnection : $dbconnection;
        return \DB::connection('mongodb')->collection($collection);
    }


    public function Peerstats_getCollection($ip = null) {
        if ($ip == null) { return response()->view('errors.404'); }

        $collection = 'peerstats';

        /* hard coded to docker */
        $node='172.17.42.1';
        return $this->Peerstats_SelectCollection()
                     ->where('node', '=', $node)
                     ->orderBy('updated_at', -1)
                     ->take(1)
                     ->get();
    }








}
