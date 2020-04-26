<?php
	trait RequestTrait
	{
		protected $request;
		protected $method;
		public $post= [];
		public $get= [];
		public function __construct()
		{
			foreach($_SERVER as $var)
			{
				$this->$var= $var;
			}
			foreach($_GET as $k=>$val) {
				$this->get[$k]= $val;
			}
			foreach ($_POST as $k => $val) {
				$this->post[$k]= $val;
			}
			$this->method= $_SERVER['REQUEST_METHOD'];
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

		public function post($val) {
			return @$this->post[$val];
		}

        public function parsePostRequest()
        {
        	static::__construct();
        	foreach($this->_post as $k=> $val) {
        		$this->$k= $val;
        	}

        }

	}

?>