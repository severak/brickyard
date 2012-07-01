<?php
class wiki{


	function index(){
		$this->page("index");
	}
	
	function page($pg){
		$text=file_get_contents($this->framework->file_path."/doc/".$pg.".txt");
		
		$this->framework->view->assign("title",$pg);
		$this->framework->view->assign("text",$text);
		$this->framework->view->display("wiki");
	}
}