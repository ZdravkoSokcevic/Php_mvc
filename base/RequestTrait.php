<?php
	trait RequestTrait
	{
		protected $request;
		public function __construct()
		{
			foreach($_SERVER as $var)
			{
				$this->$var= $var;
			}
		}
		public function input($inp)
		{
			return $this->$inp;
		}
		public function __get($input)
		{
			return @$this->$input;
		}
		public function __set($name, $val)
		{
			$this->$name= $val;
		}

		public function parseQuery()
		{
			if(empty($this->server('QUERY_STRING')))
				return;
			foreach(explode('&',$this->server('QUERY_STRING')) as $q_param) {
				$pair= explode('=', $q_param);
				if(!array_key_exists(0, $pair) || !array_key_exists(1, $pair) )
					continue;
				$k= $pair[0];
				$v= $pair[1];
				$this->$k= @$pair[1];
			}
		}

		public function server($key)
		{
			return @$this->_server[$key];
		}

	}

?>