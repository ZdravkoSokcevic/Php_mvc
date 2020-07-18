<?php
	namespace Application\Console;

	require_once __DIR__ . '/../core.php';

	class Core
	{
		private $argv = [];
		public function __construct()
		{
			$this->argv = $_SERVER['ARGV'];
		}
	}
	
?>