<?php
	
	class Response
	{
		public static function json($data)
		{
			try {
				header('Content-Type:application/json');
				echo json_encode($data);
			} catch (Exception $e) {
				
			}
			die();
		}
	}
?>