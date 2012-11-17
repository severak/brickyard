<?php
class c_roll
{
	function init()
	{
		$this->backend = new brick_roll($this->framework->libPath . DIRECTORY_SEPARATOR . "roll.ini");
	}
	
	function index()
	{
		$this->init();
		$query = (isset($_GET['q']) ? $_GET['q'] : '.');
		$listing = $this->backend->query($query);
		$linkURL = $this->framework->getRouter()->getLink('roll');
		echo $this->framework->getView()->show('roll', array('listing'=>$listing, 'linkURL'=>$linkURL));
	}
	
	function down()
	{
		$this->init();
		$package = $_POST["package"];
		header('Location: '.$this->framework->getRouter()->getLink('roll'));
	}
}