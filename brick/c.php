<?php
// Common shorthand
// Běžné zkratky
class brick_c
{
	protected function _show($tplName, $data)
	{
		return $this->framework->getView()->show($tplName, $data);
	}
	
	protected function _getLink($controller = null, $method = null, $args=array() )
	{
		return $this->framework->getRouter()->getLink($controller, $method, $args);
	}
	
	protected function _redirect($controller = null, $method = null, $args=array() )
	{
		$link = $this->framework->getRouter()->getLink($controller, $method, $args);
		header('Location: ' . $link);
	}
}