<?php
	namespace Application\Database;
	class Connection
	{
		private $conn_arr = [];
		private $type = 'default';
		private $host;
		private $port;
		private $user;
		private $pass;
		private function __construct()
		{
			$conf_file = ROOT . DS . 'config' . DS . 'database' . PHP_EXT;
			
			if(!is_file($conf_file))
				throw new \Exception('Config folder does not exists');

			$conn_arr = include $conf_file;

			$default_connection = $conn_arr[$this->type];

			if(!array_key_exists('databases', $conn_arr))
				throw new \Exception('Invalid Conf file');

			$connection_array = '';
			foreach($conn_arr['databases'] as $k => $db) {
				if($k == $default_connection) {
					$connection_array = $db;
				}
			}

			if(empty($connection_array))
				throw new \Exception('Connection array not exists, please setup the default connection properly');

			$this->conn_arr = $connection_array;

			// try {
				return $this->makeConnection();
			// } catch (\Exception $e) {
			// 	var_dump('Cannot connect into database');
			// }
			vd($conn_arr);
		}

		public function makeConnection()
		{
			// vd($this->conn_arr);
			if(
				!array_key_exists('host', $this->conn_arr) ||
				!array_key_exists('port', $this->conn_arr) || 
				!array_key_exists('username', $this->conn_arr) ||
				!array_key_exists('password', $this->conn_arr) ||
				!array_key_exists('db_name', $this->conn_arr)
			) {
				throw new \Exception('Cannot connect to db');
			}

			$this->host = $this->conn_arr['host'];
			$this->port = $this->conn_arr['port'];

			$this->user = $this->conn_arr['username'];
			$this->pass = $this->conn_arr['password'];
			$this->database = $this->conn_arr['db_name'];

			$conn_string = sprintf("mysql:host=%s;dbname=%s", $this->host, $this->database);
			try {
				$this->pdo = new \PDO($conn_string, $this->user, $this->pass);
			} catch (\PDOException $e) {
				vd($e);
			}
			// $stmt = $this->pdo->query('SELECT * FROM user_details LIMIT 20');
			
			return $this;
		}

		public static function connect()
		{
			return new static;
		}
	}
?>	