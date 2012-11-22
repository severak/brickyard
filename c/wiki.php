<?php
class c_wiki extends brick_c
{
	public $title="Brickyard framework";
	public $menu='';

	function index()
	{
		$this->_redirect("wiki", "show", array("index"));
	}
	
	function show($page="index")
	{
		$content = 'Stránka nenalezena';
		$m=$this->_model();
		if ($m->pageExists($page)) {
			$content = $m->getPage($page);
		}
		
		$markdown = new brick_markdown_wrapper;
		$content = $markdown->parse($content);
		$this->_setMenu('show', $page);
		$this->_showContent($content);
	}
	
	function edit($page="index")
	{
		$content='';
		$preview=null;
		$m=$this->_model();
		$markdown = new brick_markdown_wrapper;
		
		if (isset($_POST['text'])) {
			if (isset($_POST['prev'])) {
				$content = $_POST['text'];
				$preview = $markdown->parse($_POST['text']);
			} else {
				$m->savePage($page, $_POST['text'], 'anonymous', $_POST['comment']);
				$this->_redirect('wiki','show', array($page));
			}
		} else {
			if ($m->pageExists($page)) { $content = $m->getPage($page); }
		}
		
		$editor = $this->_show(
			'wiki_edit',
			array(
				'action'=>$this->_getLink('wiki','edit',array($page)),
				'content'=>$content,
				'preview'=>$preview
			)
		);
		$this->_showContent($editor);
	}
	
	private function _showContent($content)
	{
		echo $this->_show(
			'bootstrap',
			array(
				'brand'=>$this->title,
				'brandURL'=>$this->_getLink('wiki', 'page', array('index')),
				'title'=>$this->title,
				'main'=>$content,
				'otherNavbarContent'=>$this->menu
			)
		);
	
	}
	
	private function _setMenu($mode, $page)
	{
		$out='<div class="pull-right btn-group">';
		if ($mode=="show") {
			$out.='<a href="'.$this->_getLink('wiki', 'edit', array($page)).'" class="btn"><i class="icon-pencil"></i></a>';
		}
		$out.='<a href="'.$this->_getLink('wiki', 'history', array($page)).'" class="btn" title="history"><i class="icon-film"></i></a>';
		$out.='</div>';
		$this->menu = $out;
	}
	
	function _model()
	{
		return new model_wiki($this->framework->indexPath . DIRECTORY_SEPARATOR . 'doc');
	}
}