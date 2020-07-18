<?php
	namespace App\Core\Framework;

	class Application
	{
		private $db;
		public function connection()
		{
			return $this->db;
		}
		public function db()
		{
			return $this->db;
		}
	}

?>