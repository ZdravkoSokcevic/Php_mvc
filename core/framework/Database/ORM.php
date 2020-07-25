<?php
	namespace Application\Database;
	require_once ROOT . DS . 'core' . DS . 'framework' . DS . 'Database' . DS . 'Builder' . PHP_EXT;
	class ORM
	{
		use Builder;
		private $table = 'user_details';
		public function __construct()
		{
			// call to initialize trait
			$this->_construct();

			$data = $this->where('username', 'like', '%wright39%')
							->where(
								[
									'id', '<', '5000'
								],[
									'gender', '=' , 'female'
								]
							);
			$set = $data->exec();
			vd($set);
			// $data->where('id', '>', 5);
		}
	}

?>