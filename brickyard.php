<?php
//
// Brickyard framework by Severak
//
//I am not yet decided about license. But I prefer WTFPL.
class brickyard
{
	public $develMode = false;
	public $router = null;
	public $libPath = '.';
	public $indexPath = '.';
	public function __construct(){
		$this->router = new brickyard_router_default;
		$this->libPath = dirname(__FILE__);
	}
	
	public function getRouter()
	{
		return $this->router;
	}
	public function getIndexPath()
	{
		return $this->indexPath;
	}
	
	public function setRouter($router)
	{
		$this->router = $router;
	}
	public function setIndexPath($indexFilePath)
	{
		$this->indexPath = dirname($indexFilePath);
	}
	
	
	public function autoload($className){
		$filename=$this->libPath . DIRECTORY_SEPARATOR;
		$filename.=str_replace("_", DIRECTORY_SEPARATOR, $className);
		$filename.=".php";
		if (file_exists($filename)){
			require $filename;
			if (!class_exists($className, false)){
				throw new brickyard_exception_autoload('Class ' . $className . ' expected to be in ' . $filename . '!');
			}
		} else {
			throw new brickyard_exception_autoload('Class ' . $className . ' not found! Tried to find it in ' . $filename . '.');
		}
		
	}
	
	function error_handler($errno, $errstr, $errfile, $errline )
	{
		throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
		
	}
	
	public function exception_handler($e)
	{
		if ($this->develMode){
			$this->bluescreen($e);
		} else {
			if ($e instanceof brickyard_exception_404){
				$err = 404;
			} elseif ($e instanceof brickyard_exception_403){
				$err = 403;
			} else {
				$err = 'error';
			}	
			
			if (file_exists($this->libPath . DIRECTORY_SEPARATOR . $err . '.html')){
				ob_clean();
				echo file_get_contents($this->libPath . DIRECTORY_SEPARATOR . $err . '.html');
			}else{
				echo "An error occured. Also error page is missing.";
			}
			
		}
		
	}
	
	public function bluescreen($e)
	{
		ob_clean();
		$out="<html><head><title>error</title></head><body><h1>:-(</h1>";
		$out.="<div>" . nl2br( $e->getMessage() ) . "</div>";
		$out.="<pre>" . $e->getTraceAsString() . "</pre>";
		$out.="</body></html>";
		echo $out;
		exit;
		
	}
	
	public function init(){
		spl_autoload_register(array($this,"autoload"));
		set_error_handler(array($this,"error_handler"));
		set_exception_handler(array($this,"exception_handler"));
		
	}
	
	public function run(){
		ob_start();
		$controllerName = "c_" . $this->router->getController();
		$methodName = $this->router->getMethod();
		$args = $this->router->getArgs();
		try {
			$controllerInstance = new $controllerName;
		} catch(brickyard_exception_autoload $e) {
			throw new brickyard_exception_404($e->getMessage() );
		}
		$controllerInstance->framework=$this;
		$call=array($controllerInstance, $methodName);
		if (is_callable($call)){
			call_user_func_array($call,$args);
		}else{
			throw new brickyard_exception_404('Method ' . $methodName . ' is invalid!');
		}
		
	}
}

class brickyard_exception_autoload extends Exception{}

class brickyard_exception_404 extends Exception{}

class brickyard_exception_403 extends Exception{}

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