<?php

	$included_files = get_included_files();
	// var_dump(get_included_files()) or die();
	 spl_autoload_extensions(".php");
	spl_autoload_register('mvc_autoloader');
?>