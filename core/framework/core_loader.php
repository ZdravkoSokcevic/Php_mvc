<?php

	$included_files = get_included_files();

	$core_folder = realpath(ROOT . 'core');
	$core_folder_files = [];

	getDirContents($core_folder, $core_folder_files);

	/* 
	|
	|	ToDo this not working as expected
	|
	*/
	// $diff = array_intersect($core_folder_files, $included_files);
	foreach($core_folder_files as $file) {
		if(file_exists($file))
			require_once $file;
		else {
			var_dump($file);
			throw new \Exception('Fajl ne postoji');
		}
	}

?>