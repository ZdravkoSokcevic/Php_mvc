<?php
	namespace Application\Console;
	require_once __DIR__ . '/../core.php';
	require_once __DIR__ . '/../core_loader.php';
	use Application\Console\Exception\Exception as ConsoleException;
	use Application\Console\InternalServer;

	require_once __DIR__ . '/./InternalServer' . PHP_EXT;
	
	class Core
	{
		use InternalServer;

		private $argv = [];
		private $commandsArray = [];
		private $commands = [
			'run',
			'build:controller',
			'build:model',
			'build:middleware'
		];
		public function __construct()
		{
			// Remove first argument, this is script name
			$this->argv = array_splice($_SERVER['argv'], 1 , 1);
			return $this->parseCommands();

			if(empty($this->argv) || count($this->argv)<=1)
				throw new \Application\Console\Exception\Exception('Arguments are empty', $this->argv);
		}

		private function parseCommands()
		{	
			// Call function which match with second parameter
			// eg php zdravko run -> we call runCommand function
			$first = $this->argv[0];
			$this->argv = array_splice($this->argv, 1, 1);
			return $this->{strtolower($first).'Command'}();
		}



		public function __call($method, $args)
		{
			vd('U call');
		}

		private function runCommand()
		{
			if(empty($this->argv)
				|| !in_array('--port', $this->argv)
				|| !in_array('--host', $this->argv)
			) {
				$this->port = 8888;
				$this->host = '127.0.0.1';
			}
			
			$this->serve();
			vd('U run command');
		}

		private function _command()
		{

		}
	}
	
?>