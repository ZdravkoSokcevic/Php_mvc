<?php
	// namespace Application\View;
	class View
	{
		private $path;
		protected $default_path;
		protected $vars = [];

		public function __construct()
		{
			$this->default_path = realpath(ROOT . DS . 'views' . DS . strtolower(str_replace("Controller", "", get_class($this))));
		}

		public function setVars(array $vars)
		{
			foreach($vars as $key => $val)
			{
				$this->{$key} = $val;
			}
		}

		public function set($key, $val)
		{
			$this->vars[$key] = $val;
			$this->{$key} = $val;
		}

		public function render($path = 'default')
		{
			$path_source = $path;
			$this->path = $path;

			$this->validateViewExists();

			if($path_source == 'default')
				$this->renderDefaultPath();

			$this->renderHtml();
		}

		protected function validateViewExists()
		{
			// First we check if default path
			if(($this->path == 'default' && !is_dir($this->default_path)) || !is_file($this->default_path . DS .  'index.php'))
				throw new \Exception('Folder or file not exists in view folder');

			if(!is_dir(ROOT . $this->default_path) && !is_file($this->default_path . DS . 'index.php'))
				throw new \Exception('Folder '. $this->path . 'doesn\'t exists');

			$this->path = str_replace(".","/", $this->path);
			$full_path = ROOT . DS . 'views' . DS;
			if(is_array($this->path))
				$full_path .= $this->path[0];
			else $full_path .= $this->path;

			if(
				(
					!is_dir($full_path) &&
					!is_file($full_path) &&
					!is_file($full_path . PHP_EXT)
				) && $this->path != 'default'
			)
				throw new \Exception('Folder ' . $this->path . ' doesn\'t exists');

			// if(!strpos('.php' ,$full_path)) 
			// 	$full_path .= '.php';

			$this->path = $full_path;
		}

		public function renderDefaultPath()
		{
			$class_name = str_replace("Controller", "", __CLASS__);
			$default = $this->default_path . DS . "index.php";
			extract($this->vars);
			require_once $default;
			exit; 
		}

		public function renderHtml()
		{
			if(is_dir($this->path))
				$this->path .= DS . 'index' . PHP_EXT;

			if(!strpos($this->path, '.php'))
				$this->path .= PHP_EXT;

			extract($this->vars);

			include_once $this->path;
			die();
		}
	}

?>