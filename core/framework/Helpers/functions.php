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

	function getDirContents($dir, &$results = []){
	    $files = scandir($dir);

	    foreach($files as $key => $value){
	        $path = realpath($dir . DS . $value);
	        if(!is_dir($path)) {
	            $results[] = $path;
	        } else if($value != "." && $value != "..") {
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

	function mvc_autoloader()
	{
		$allowed_extensions = ['php'];
		$core_folder = realpath(ROOT . DS . 'core');
		$core_folder_files = [];
		getDirContents($core_folder, $core_folder_files);
		foreach($core_folder_files as $file) {
			$parts = explode('.', $file);
			$ext = $parts[count($parts) - 1];
			if(isset($ext) && in_array($ext, $allowed_extensions))
			{
				if(file_exists($file) && !in_array($file, get_included_files()))
					require_once $file;
				else {
					// var_dump($file);
					// throw new \Exception('Fajl ne postoji ' . $file);
				}
			}
		}
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


?>