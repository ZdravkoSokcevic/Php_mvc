<?php
	namespace Application\Console\Exception;

	class Exception
	{
		private $message;
		private $subject;
		private $action;
		public function __construct($message = '', $action)
		{
			$this->message = $message;
			\vd($message);
		}


	}
?>