<?php
class brickyard{
	public $errors=array();
	public $controller="home";
	public $method="index";
	public $debug=false;
	
	public function __construct(){
		$this->file_path=dirname(__FILE__);
		$this->router=new brickyard_router();
		$this->view=new brickyard_view();
		$this->view->framework=$this;
	}
	
	function init(){
		set_error_handler(array($this,"error_handler"));
		spl_autoload_register(array($this,"autoload"));
	}
	
	function autoload($name){
		$filename=$this->file_path.DIRECTORY_SEPARATOR;
		$filename.=str_replace("_",DIRECTORY_SEPARATOR,$name);
		$filename.=".php";
		include $filename;
	}
	
	public function error_handler($errno,$errmsg,$errfile="",$errline=-1){
		$this->errors[]=array("no"=>$errno,"message"=>$errmsg,"file"=>$errfile,"line"=>$errline);
	}
	
	function bluescreen($message){
		if ($this->debug){
			ob_clean();
			$out="<html><head><title>error</title></head><body><h1>:-(</h1>";
			$out.="<p>".$message."</p>";
			$out.="<pre>"; 
			echo $out;
			debug_print_backtrace();
			$out="</pre>";
			$out.="</body></html>";
			echo $out;
			exit;
		}
	}

	public function run(){
		ob_start();
		$route=$this->router->analyze();
		if ($route["controller"]!=""){ $this->controller=$route["controller"]; }
		if ($route["method"]!=""){ $this->method=$route["method"]; }
		$controller_path=$this->file_path.DIRECTORY_SEPARATOR."c".DIRECTORY_SEPARATOR.$this->controller.".php";
		if (file_exists($controller_path)){
			include $controller_path;
			$controller_instance=new $this->controller;
			$controller_instance->framework=$this;
			$call=array($controller_instance,$this->method);
			if (is_callable($call)){
				call_user_func_array($call,$route["args"]);
			}else{
				$this->bluescreen("Invalid method ".$this->method."!");
			}
		}else{
			$this->bluescreen("Invalid controller ".$this->controller."!");
		}
		//var_dump($this->errors);
	}
}

class brickyard_router{
	public $base_url="/";
	
	public function analyze(){
		$ret=array("controller"=>"","method"=>"","args"=>array());
		$path=( isset($_SERVER["PATH_INFO"]) ? explode("/",$_SERVER["PATH_INFO"]) : array() );
		if (count($path)>1){$ret["controller"]=$path[1];}
		if (count($path)>2){$ret["method"]=$path[2];}
		$ret["args"]=array_slice($path,3);
		return $ret;
	}
	
	public function route($r){
		$url=$this->base_url;
		if (isset($r["controller"])){
			$url.=$r["controller"];
			if (isset($r["method"])){
				$url.="/".$r["method"];
				if (isset($r["args"])){
					$url.="/".join($r["args"],"/");
				}
			}
		}
		return $url;
	}
}

class brickyard_view{
	public $data=array();
	
	public function assign($key,$value=""){
		$this->data[$key]=$value;
	}
	
	public function display($view){
		extract($this->data);
		include "v/".$view.".php";
	}
}