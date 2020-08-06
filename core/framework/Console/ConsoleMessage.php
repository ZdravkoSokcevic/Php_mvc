<?php
	class ConsoleMessage
	{
		private $startCode = "\033[";
		private $red = "";
		private $finishCode = "\033[0m";
		private $fgCode;
		private $bgCode;
		private $m;
		private $fgColors = [
			'black' => '0;30',
			'dark_gray' => '1;30',
			'blue' => '0;34',
			'light_blue' => '1;34',
			'green' => '0;32',
			'light_green' => '1;32',
			'cyan' => '0;36',
			'red' => '0;31',
			'light_red' => '1;31',
			'purple' => '0;35',
			'light_purple' => '1;35',
			'brown' => '1;33',
			'light_gray' => '0;37',
			'white' => '1;37'
		];
		private $bgColors = [
			'black' => '40',
			'red' => '41',
			'green' => '42',
			'yellow' => '43',
			'blue' => '44',
			'magenta' => '45',
			'cyan' => '46',
			'light_gray' => '47'
		];
		public function __construct($message, $type='success')
		{
			$this->m = $message;
			$this->message = $message;
			switch($type)
			{
				case 'success':
				{
					$this->prepareSuccessMessage();
					break;
				}
				case 'error':
				{
					$this->prepareErrorMessage();
					break;
				}
				case 'warning':
				{
					break;
				}
				default: $this->prepareErrorMessage();
			}

			$this->messageBuilder();
			$this->show();

			if($type == 'error')
				$this->getDebugBacktrace();

			if($type == 'success')
				die();
		}

		public function show()
		{
			echo $this->message;
			echo "\n\n\n";
		}

		public function getDebugBacktrace()
		{
			\printStackTrace();
		}

		public function prepareErrorMessage()
		{
			$this->fgCode = $this->getFgValueByKey('white');
			$this->bgCode = $this->getBgValueByKey('red');
		}

		public function prepareSuccessMessage()
		{
			$this->fgCode = $this->getFgValueByKey('green');
			$this->bgCode = $this->getBgValueByKey('black');
		}

		private function messageBuilder()
		{
			$fullMessage = "";

			// Foreground Color
			$fullMessage .= $this->startCode;
			$fullMessage .= $this->fgCode;
			$fullMessage .= 'm';

			// Background Color
			$fullMessage .= $this->startCode;
			$fullMessage .= $this->bgCode;
			$fullMessage .= "m";
			$fullMessage .= " ";

			$fullMessage .= $this->message;

			$fullMessage .= " ";
			$fullMessage .= $this->finishCode;
			$this->message = $fullMessage; 
		}

		private function getFgValueByKey($key)
		{
			foreach($this->fgColors as $code => $color) {
				if($code == $key) 
					return $color;
			}
			return null;
		}

		private function getBgValueByKey($key)
		{
			foreach($this->bgColors as $code => $color) {
				if($code == $key) {
					return $color;
				}
			}
			return null;
		}
	}
?>