<?php
	namespace Application\Console;

	trait InternalServer
	{
		private $host;
		private $port;
		private $path;

		public function serve()
		{
			$this->path = (isset($this->path) && !empty($this->path)) ? './' . $this->path : './routing';
			$commandString = "php -S $this->host:$this->port -t $this->path";
			$out = [];
			echo "\033[46m";
			echo "Application run on $this->host:$this->port";
			echo "\033[0m";
			echo exec($commandString);
			// var_dump($out);
		}
	}
?>