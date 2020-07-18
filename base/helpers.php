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
		foreach($ex->getTrace() as $file)
			$files[] = [
				'file' => $file['file'],
				'line' => $file['line']
			];

		vd($files);
	}


?>