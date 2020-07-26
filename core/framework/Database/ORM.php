<?php
	namespace Application\Database;
	require_once ROOT . DS . 'core' . DS . 'framework' . DS . 'Database' . DS . 'Builder' . PHP_EXT;
	require_once ROOT . DS . 'core' . DS . 'framework' . DS . 'Database' . DS . 'DB' . PHP_EXT;
	use Application\Database\DB;
	class ORM
	{
		use Builder;
		private $table = 'user_details';
		public function __construct()
		{
			// call to initialize trait
			$this->_construct();
			$data = DB::table('user_details')->where('username', 'like', '%wright39%')
							->where(
								[
									'user_id', '<', '5000'
								],[
									'gender', '=' , 'female'
								])
							->orderBy('user_id', 'desc')
							->exec();
			$another_data = static::where('username', 'like', '%wright39')->where('user_id', '<', 5000)->where('gender', '=', 'female')->orderBy('user_id', 'desc')->exec();
			// vd([count($another_data), count($another_data)]);

			header('Content-Type: application/json');
			echo json_encode($data);
			die();
			// $data->where('id', '>', 5);
		}
	}

?>