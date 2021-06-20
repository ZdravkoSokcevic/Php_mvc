<?php
	function dd($var) {
		$data= '';
		try {
			$data= json_encode($var);
		} catch (Exception $e) {
			
		}
		echo $data;
		die();
	}

	function ppr($var) {
		echo '<pre>';
		print_r($var);
		echo '<pre>';
	}

	function pr($var) {
		print_r($var);
		die();
	}

	function vd($var) {
		var_dump($var);
		die();
	}

	function getDirContents($dir, &$results = [], $ignore = []){
		if(empty($dir))
			return;
	    $files = scandir($dir);

	    foreach($files as $key => $value){
	        $path = realpath($dir . DS . $value);
	        if(!is_dir($path)) {
	            $results[] = $path;
	        } else if($value != "." && $value != ".." && !in_array($path, $ignore)) {
	            getDirContents($path, $results);
	            // $results[] = $path;
	        }
	    }

	    return $results;
	}

	function printStackTrace()
	{
		$ex = new \Exception();
		$files = [];
		ini_set('error_reporting', false);
		// vd($ex->getTrace());
		foreach($ex->getTrace() as $file)
			$files[] = [
				'file' => @$file['file'],
				'line' => $file['line'],
				'function' => file['function'],
				'class' => file['class'],
			];

		vd($files);
	}

	function isAssoc(array $arr)
	{
	    if (array() === $arr) return false;
	    return array_keys($arr) !== range(0, count($arr) - 1);
	}

	function mvc_load_folder($folder, $ignore = [])
	{
		$allowed_extensions = ['php'];
		$files = [];
		getDirContents($folder, $files, $ignore);
		foreach($files as $file) {
			if(empty($file))
				continue;
			$parts = explode('.', $file);
			$ext = $parts[count($parts) - 1];
			if(isset($ext) && in_array($ext, $allowed_extensions))
			{
				// if(file_exists($file) && !in_array($file, get_included_files()))
				if(file_exists($file) && !in_array($file, get_included_files()))
					require_once $file;
				else {
					// var_dump($file);
					// throw new \Exception('Fajl ne postoji ' . $file);
				}
			}
		}
	}

	function mvc_autoloader()
	{
		$folders = [
			realpath(FRAMEWORK . DS . 'Classes'),
			realpath(FRAMEWORK . DS . 'Console'),
			realpath(FRAMEWORK . DS . 'Controller'),
			realpath(FRAMEWORK . DS . 'Database'),
			realpath(FRAMEWORK . DS . 'Handler'),
			realpath(FRAMEWORK . DS . 'Helpers'),
			realpath(FRAMEWORK . DS . 'Model'),
			realpath(ROOT . DS . 'model'),
			realpath(ROOT . DS . 'controller'),
		];
		$ignore = [
			realpath(FRAMEWORK . DS . 'views')
		];
		foreach($folders as $folder)
			mvc_load_folder($folder, $ignore);

		if(is_dir(ROOT . DS . 'vendor') && file_exists(ROOT . DS . 'vendor' . DS . 'autoload.php'))
			require_file('vendor.autoload');
		$core_folder = realpath(ROOT . DS . 'core');
	}

	function require_file($file)
	{
		if(!is_string($file))
			return;
		$filename = $file;
		if(strpos($file, '.'))
		{
			$filename = str_replace('php', '', $filename);
			$filename = str_replace('.', '/', $filename);
			$filename .= '.php';
			@require_once ROOT . DS . $filename;
		}
	}

	function app($params)
	{
		if(isset($app))
			return $app->get($params);

		return new Application($params);
	}

	/*
	|	@param $path | string|null
	|   @return string - relative path 
	|
	|
	 */
	function path($path='')
	{
		if(empty($path))
			return false;

		if(is_file($path))
			return $path;

		// go from ROOT folder
		$path = ROOT . DS . $path;

		$extensions = [
			'php',
			'ctp',
			'html',
			'css',
			'js',
			'jpg',
			'png',
		];

		// Try with different extensions
		// ordered by extensions array priority
		foreach($extensions as $ext)
		{
			if(is_file($path . DOT . $ext))
				return $path . DOT . $ext;
		}

		// $default_extension = 'php';
		$has_extension = false;
		$extension = 'php';

		// Try to find extension
		if(strpos($path, '.') !== false)
		{
			$parts = explode('.', $path);
			$last_part = $parts[count($parts) - 1];
			// $parts
			if(in_array($last_part, $extensions))
				$has_extension = true;

			if($has_extension)
			{
				foreach ($extensions as $ext) {
					if($ext == $last_part)
						$extension = $ext;
				}
				array_pop($parts);
			}

			// add extension if is not file
			if(is_file(implode('/', $parts)))
				return implode('/', $parts);

			// try adding extension
			if(is_file(implode('/', $parts) . DOT . $extension))
				return implode('/', $parts) . DOT . $extension;
			
		}else {
			// try with extensions
			foreach($extensions as $ext)
				if(is_file($path . DOT . $ext))
					return $path . DOT . $ext;
		}
		return false;
	}

	function print_to_console($data)
	{
		file_put_contents('php://stderr', print_r($data, TRUE));
	}

	function log_error($data)
	{
		log_error(print_r($data, TRUE)); 
	}

?>