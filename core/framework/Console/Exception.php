<?php
	namespace Application\Console\Exception;
	use ConsoleMessage;

	class Exception
	{
		private $message;
		private $subject;
		private $action;
		public function __construct($message = '', $action)
		{
			$this->message = $message;
			$this->action = $action;
			return $this->{$action . 'Exception'}();
			\vd($message);
		}

		private function buildControllerCommandException()
		{
			$this->message .= " thrown in " . $this->action;
			return new ConsoleMessage($this->message, 'error');
		}


	}
	
?>