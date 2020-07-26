<?php

	namespace Application\Database;

	require_once ROOT . DS . 'core' . DS . 'framework' . DS . 'Database' . DS . 'Builder' . PHP_EXT;
	require_once ROOT . DS . 'core' . DS . 'framework' . DS . 'Database' . DS . 'Connection' . PHP_EXT;

	class DB
	{
		use Builder;
		
		private function __construct()
		{
			$this->connection = Connection::connect();
		}

		public static function table($table)
		{
			$instance = new static;
			$instance->table = $table;
			return $instance;
		}
	}

?>