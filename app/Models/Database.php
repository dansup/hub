<?php
namespace App\Models;

use \PDO;
use \App\Config\App;
class Database Extends PDO {
	
	public $db;
	
	function __construct()
	{
		try
		{
		return  parent::__construct(DB_TYPE. ':host=' .DB_HOST. ';dbname=' .DB_NAME. ',' .DB_USER. ',' .DB_PASS);
		}
		catch (PDOException $e)
		{
		throw new Exception( 'Connection failed: ' . $e->getMessage() );
		}
	}
}