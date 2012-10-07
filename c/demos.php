<?php
class c_demos
{
	function _getLink($name, $method, $args=array())
	{
		$router = $this->framework->getRouter();
		return '<a href="' . $router->getLink('demos', $method, $args). '">' . $name . '</a>';
	}
	
	function index()
	{
		echo '<h1>Demos</h1>';
		echo '<ul>';
		echo '<li>' . $this->_getLink('exception handling', 'exceptions') . '</li>';
		echo '<li>' . $this->_getLink('source code brownser', 'source', array('c_demos', 'source')) . '</li>';
		echo '<li>' . $this->_getLink('this controller source', 'source', array('c_demos')) . '</li>';
		echo '</ul>';
	}
	
	function exceptions()
	{
		try {
			throw new Exception(
				'In develMode exception reports looks like this page.' . PHP_EOL .
				'If you like this report, you can get it by calling $this->framework->bluescreen($e); like in this page.'
			);
		} catch (Exception $e) {
			$this->framework->bluescreen($e);
		}
	}
	
	function source($controller="c_demos", $method=null)
	{
		if ($method){
			$code = new ReflectionMethod($controller, $method);
		} else {
			$code = new ReflectionClass($controller);
		}
		$sourceName = $code->getFileName();
		if ($sourceName){
			$file = file($sourceName);
			$start = $code->getStartLine();
			$stop = $code->getEndLine();
			echo '<pre>';
			for ($i = $start-1; $i<$stop; $i++){
				echo htmlspecialchars($file[$i]);
			}
			echo '</pre>';
		} else {
			throw new Exception("Class " . $controller . " is unreachable for source brownser.");
		}
	}
}