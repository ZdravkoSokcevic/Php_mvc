<?php
    require __DIR__ . '/./RequestTrait.php';
    require __DIR__ . '/./Request.php';
    class Router implements Request
    {
        use RequestTrait;
        // use RequestTrait {
        //     post as protected;
        // };
        // use RequestTrait {
        //   RequestTrait::__construct insteadof RequestTrait;
        // }

        private $route;
        private $action;
        private $_vars= [];
        private $_controller;
        private $_action;
        private $_server;
        private $_get;
        private $_post;
        public function __construct()
        {
            // $this-> __rt_construct();
            $this->_server= $_SERVER; 
            $this->_post= $_POST;
            $this->_get= $_GET;
        }

        public function capsulateRequest()
        {
            
        }

        public function pathExploder()
        {
            $_req = array_values(array_filter(explode('/',$this->server('PATH_INFO'))));
            $_route = array_values(array_filter(explode('/', $this->route)));
            if(empty($_req)) {
                return $this->home();
            }
            foreach ($_route as $i=>$path) {
                $req= $_req[$i];
                // check if not interpolation eg.. user/{id}
                if(strpos($path, '{') !==false) {
                    $var= str_replace('{', '', $path);
                    $var= str_replace('}', '', $var);
                    $this->_vars[$var]= $req;
                }else {
                    if($path!==$req)
                        return false;
                }
            }
            return true;
        }

        public function isPathOrParam($uri)
        {
            return substr($uri,0,1)==':';
        }

        public function routeMatcher()
        {
            $match= $this->pathExploder();
            if(!$match)
                return;

            $this->parseQuery();
            $this->parseAction();
            $this->validateControllerExists();
            $this->dispatch();
        }

        public function dispatch()
        {
            $controller= new $this->_controller($this);
            if(!method_exists($controller, $this->_action))
                throw new Exception('Bad mathod call');

            $class= new ReflectionClass($controller);
            $ref= new ReflectionMethod($controller, $this->_action);
            $args= [];
            $has_request=false;
            foreach ($ref->getParameters() as $key=>$value) {
                if(gettype($value)==='object') {
                    if($value->getClass() && $value->getClass()->name==='Request') {
                        $has_request= true;
                            $args[]= $this;
                    }
                    else {
                        $args[]= $this->_vars[$value->name];
                    }
                }
            }
            $res= '';
            if($has_request) {

                if(count($args)==1)
                    $res= $ref->invokeArgs($controller, $args[0]);
                $res= $ref->invokeArgs($controller, $args);
            }
            else $res= $ref->invokeArgs($controller, $args);
            // echo $res;
            // die();
        }

        public function parseAction()
        {
            $arr= explode('@', $this->action);
            if((count(array_intersect_key(array_flip([0,1]), $arr)) === count([0,1]))===false)
                throw new Exception('Controller not properly defined');
            $this->_controller= $arr[0];
            $this->_action= $arr[1];
        }

        public function validateControllerExists()
        {
            $controller_path= __DIR__ . '/../controller/';
            $files= array_diff(scandir($controller_path) , ['.','..']);
            foreach ($files as $file) {
                if(explode('.', $file)[0] === $this->_controller) {
                    require_once __DIR__ . '/../controller/' . $file;
                    return;
                }
            }
            throw new Exception('Controller does\'t exists');
        }

        public function get($path='', $action)
        {
            if(!is_string($path) || empty($action) || !is_string($action))
                return;

            if($this->server('REQUEST_METHOD')!=='GET') {
                // ToDo check if router has method post
                return;
            }

            $this->route= $path;
            $this->action= $action;
            if(empty($path)) {
                return $this->home();
            }

            $this->routeMatcher();
        }

        public function post($path='', $action)
        {
            if(!is_string($path) || empty($action) || !is_string($action))
                return;

            if($this->server('REQUEST_METHOD')!=='POST')
                return;

            $this->route= $path;
            $this->action= $action;
            if(empty($path))
                return $this->home();
            $this->routeMatcher();
        }

        public function home()
        {
            switch($this->server('REQUEST_METHOD'))
            {
                case 'GET':
                {
                    $this->parseQuery();
                    $this->parseGetParams();
                    break;
                }
                case 'POST':
                {
                    $this->parsePostRequest();
                    $this->parseQuery();
                    break;
                }
            }
            $this->parseAction();
            $this->validateControllerExists();
            // pr($this);
            return $this->routeMatcher();
        }




    }

?>