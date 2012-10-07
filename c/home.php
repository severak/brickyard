<?php
class c_home{
	function index(){
		echo "<h1>It's working!</h1>";
		echo 'see some <a href="' .$this->framework->getRouter()->getLink('demos') . '">demos</a>';
	}
}
