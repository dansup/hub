<?php namespace App;

use Jenssegers\Mongodb\Model as Eloquent;
use Moloquent;
use Log;
use Input;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Peerstats extends Moloquent {

	protected $collection = 'peerstats';
	protected $connection = 'mongo';

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
}
