<?php
	interface Request
	{
		public function dispatch();
        public function parseAction();
	}

?>