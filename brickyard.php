<?php
//
// Brickyard framework by Severak
//
//I am not yet decided about license. But I prefer WTFPL.
class brickyard
{
	public $develMode = true;
	public $router = null;
	public $path = '.';
	
	public function __construct(){
		$this->router = new brickyard_router_default;
		$this->path = dirname(__FILE__);
	}
	
	public function getRouter()
	{
		return $this->router;
	}
	
	public function setRouter($router)
	{
		$this->router = $router;
	}
	
	
	public function autoload($className){
		$filename=$this->path.DIRECTORY_SEPARATOR;
		$filename.=str_replace("_", DIRECTORY_SEPARATOR, $className);
		$filename.=".php";
		if (file_exists($filename)){
			require $filename;
			if (!class_exists($className, false)){
				throw new Exception('Class ' . $className . ' expected to be in ' . $filename . '!');
			}
		} else {
			throw new Exception('Class ' . $className . ' not found! Tried to find it in ' . $filename . '.');
		}
		
	}
	
	function error_handler($errno, $errstr, $errfile, $errline ) {
	
		throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
	
	}
	
	
	
	public function bluescreen($e){
		if ($this->develMode){
			ob_clean();
			$out="<html><head><title>error</title></head><body><h1>:-(</h1>";
			$out.="<div>" . nl2br( $e->getMessage() ) . "</div>";
			$out.="<pre>" . $e->getTraceAsString() . "</pre>";
			$out.="</body></html>";
			echo $out;
			exit;
			
		} else {
			echo 'Silent error!';
		}
		
	}
	
	public function init(){
		spl_autoload_register(array($this,"autoload"));
		set_error_handler(array($this,"error_handler"));
		set_exception_handler(array($this,"bluescreen"));
		
	}
	
	public function run(){
		ob_start();
		$controllerName = "c_" . $this->router->getController();
		$methodName = $this->router->getMethod();
		$args = $this->router->getArgs();
		$controllerInstance = new $controllerName;
		$controllerInstance->framework=$this;
		$call=array($controllerInstance, $methodName);
		if (is_callable($call)){
			call_user_func_array($call,$args);
		}else{
			throw new Exception('Method ' . $methodName . ' is invalid!');
		}
		
	}
}

interface brickyard_router_interface

{

	public function getController();

	public function getMethod();

	public function getArgs();

	public function getLink($controller = null, $method = null, $args=array() );

	

}

class brickyard_router_default implements brickyard_router_interface

{

	public $controller = "home";

	public $method = "index";

	public $args = array();

	

	function analyze()

	{

		$path=( isset($_SERVER["PATH_INFO"]) ? explode("/",$_SERVER["PATH_INFO"]) : array() );

		if (count($path)>1 and $path[1]!=''){$this->controller=$path[1];}

		if (count($path)>2  and $path[2]!=''){$this->method=$path[2];}

		if (count($path)>3){$this->args=array_slice($path,3);}

	}

	

	public function getController()

	{

		$this->analyze();

		return $this->controller;

	}

	

	public function getMethod()

	{

		$this->analyze();

		return $this->method;

	}

	

	public function getArgs()

	{

		$this->analyze();

		return $this->args;

	}

	

	public function getLink($controller = null, $method = null, $args=array() )

	{

		$url = $_SERVER["SCRIPT_NAME"];

		if ($controller){

			$url .= '/' . $controller;

			if ($method){

				$url .= '/' . $method;

				if (count($args)>0){

					$url .= '/' . implode('/', $args);

				}

			}

		}

		return $url;

	}

}