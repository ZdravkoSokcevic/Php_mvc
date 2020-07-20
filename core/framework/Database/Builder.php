<?php
	namespace Application\Database;
	require_once __DIR__ . DS . 'Connection' . PHP_EXT;
	class Builder
	{

		public function __construct()
		{
			$this->connection = Connection::connect();
			vd($this->connection);
		}
	}

?>