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
		return  parent::__construct("mysql:host=localhost;dbname=hubdev", DB_USER, DB_PASS);
		}
		catch (PDOException $e)
		{
		throw new Exception( 'Connection failed: ' . $e->getMessage() );
		}
	}
}