<?php

	$included_files = get_included_files();

	$core_folder = realpath(ROOT . 'core');
	$core_folder_files = [];

	getDirContents($core_folder, $core_folder_files);

	$diff = array_intersect($included_files, $core_folder_files);

	foreach($diff as $file) 
		require_once $file;

	
?>