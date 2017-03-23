<?php
	include_once("../config.php.ini");

	abstract class AbstractModel
	{
		protected $db;
		
		protected static function connectBd()
		{	
			return new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME); ;
		}
	}