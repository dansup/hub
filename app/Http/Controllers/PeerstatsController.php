<?php namespace App\Http\Controllers;

use Input;
use Log;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use App\Peerstats;

class PeerstatsController extends Controller {

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($req = 'req')
	{
        $ps = new Peerstats(Input::ip());
        $ps->peerstats = Input::json()->get('peerstats');
        $result = $ps->PeersUpdate();

		return(json_encode(['result' => $result ]));
	}

}
