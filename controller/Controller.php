<?php
	class Controller
	{
		protected $request;
		public function __construct($req)
		{
			$this->request= $req;
		}
	}

?>