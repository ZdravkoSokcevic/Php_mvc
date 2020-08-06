<?php
	namespace Application\Console;
	require_once __DIR__ . '/../core.php';
	require_once __DIR__ . '/../core_loader.php';
	use Application\Console\Exception\Exception as ConsoleException;
	use Application\Console\InternalServer;
	use ConsoleMessage;

	require_once __DIR__ . '/./InternalServer' . PHP_EXT;
	
	class Core
	{
		use InternalServer;

		private $argv = [];
		private $commandsArray = [];
		private $commands = [
			'run' => 'run',
			'build:controller' => 'buildController',
			'build:model' => 'buildModel',
			'build:middleware' => 'buildMiddleware'	
		];
		public function __construct()
		{
			// Remove first argument, this is script name
			$this->argv = $_SERVER['argv'];
			array_splice($this->argv, 0 , 1);
			return $this->parseCommands();

			if(empty($this->argv) || count($this->argv)<=1)
				throw new \Application\Console\Exception\Exception('Arguments are empty', $this->argv);
		}

		private function parseCommands()
		{	
			// Call function which match with second parameter
			// eg php zdravko run -> we call runCommand function
			$first = $this->argv[0];
			array_splice($this->argv, 0, 1);
			foreach($this->commands as $k => $val) {
				if($k == $first) {
					// vd($val);
					return $this->{strtolower($val).'Command'}();
				}
			}

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

			// Parse port and host
			foreach ($this->argv as $index => $argv) {
				$action = $argv;
				if($action == '--port' && ctype_digit($this->argv[1])) {
					$this->port = $this->argv[1];
					array_splice($this->argv, 0, 2);
				}else {
					array_splice($this->argv, 0 , 2);
				}

				if($action == '--host' && ctype_digit($this->argv[1])) {
					$this->host = $this->argv[1];
					array_splice($this->argv, 0, 2);
				}else {
					array_splice($this->argv, 0, 2);
				}
			}
			
			$this->serve();
		}

		private function buildControllerCommand()
		{
			$template = include(ROOT . DS . 'core' . DS . 'framework' . DS . 'Controller' . DS . 'template' . PHP_EXT );

			if(!count($this->argv))
				return new ConsoleException('Controller name does not be empty', __FUNCTION__);

			$name = $this->argv[0];
			if(empty($name))
				throw new \Exception('Controller name does not be empty');

			if(strpos($name, 'Controller') == false)
				$name.= 'Controller';

			$controller = sprintf($template, $name);
			$controller = trim($controller);

			$controller_full_path = ROOT . DS . 'controller' . DS . $name . PHP_EXT;
			if(is_file($controller_full_path))
				return new ConsoleException('Controller exists', __FUNCTION__);

			$success = file_put_contents($controller_full_path, $controller);
			new ConsoleMessage('Controller created successifully', 'success');

		}

		private function buildModelCommand()
		{
			$template = include(ROOT . DS . 'core' . DS . 'framework' . DS . 'Model' . DS . 'template' . PHP_EXT);

			if(!count($this->argv))
				return new ConsoleException('Model name does not be empty', __FUNCTION__);

			$name = $this->argv[0];
			if(empty($name))
				return new ConsoleException('Model name does not be empty', __FUNCTION__);

			$model = sprintf($template, $name);
			$model = trim($model);

			$model_path = ROOT . DS . 'model' . DS . $name . PHP_EXT;

			if(is_file($model_path))
				return new ConsoleException('Model exists', __FUNCTION__);

			$success = file_put_contents($model_path, $model);
			new ConsoleMessage('Model created successifully', 'success');
			// vd($success);
		}



		private function _command()
		{

		}
	}
	
?>