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

	function pr($var) {
		print_r($var);
		die();
	}

	function vd($var) {
		var_dump($var);
		die();
	}


?>