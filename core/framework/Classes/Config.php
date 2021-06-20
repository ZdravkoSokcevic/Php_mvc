<?php 
	class Config
	{
		protected $config = [];
		public function __construct()
		{
			$main_config = $this->loadListFilesFromDir('core.config');
			foreach($main_config as $file) {
				$this->loadFileToConfig('core.config.'.$file);
			}
			// vd($main_config);
		}

		public function read($key)
		{
			return static::loadConfig($key);
		}

		public function write($key, $value = null)
		{
			if(is_null($value))
				return;

			static::writeConfig($key, $value);
		}

		protected function loadListFilesFromDir($dir)
		{
			$parts = explode('.', $dir);
			$part_str = ROOT;
			foreach($parts as $part)
			{
				$part_str .= DS . $part;
			}

			if(is_dir($part_str))
				return array_diff(scandir($part_str), ['.', '..']);

			return [];
		}

		protected function loadFileToConfig($file)
		{
			$count = substr_count($file, '.');
			$file = preg_replace('/\./', '/', $file, (substr_count($file, '.') - 1));;
			$file = ROOT . DS . $file;

			if(!is_file($file))
				return;

			$file_content = @require_once $file;
			$this->addToConfigurator($file_content);
		}

		protected function addToConfigurator($config, $overall = null)
		{
			// if(is_null($overall))
			// 	$overall = $this->config;

			// // ToDo: implement config array filling with values
			// foreach($config as $k => $val) {
			// 	if(is_null($overall))
			// 		$overall[$k] = $val;
			// 	else if(is_null($overall[$k]) && !array_key_exists($k, $overall))
			// 		$overall[$k] = $val;
			// 	else if(is_array($val))
			// 		$this->addToConfigurator($val, $overall);
			// 	else $overall[$k] = $val;
			// }
		}
	}

?>