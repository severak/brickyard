<?php
class model_wiki
{
	private $_dataDir = '.';

	function __construct($dataDir)
	{
		$this->_dataDir = $dataDir;
	}
	
	private function _pageFN($page)
	{
		return $this->_dataDir . DIRECTORY_SEPARATOR . $page . '.md';
	}
	
	private function _logFN($page)
	{
		return $this->_dataDir . DIRECTORY_SEPARATOR . $page . '.log';
	}
	
	private function _revFN($page, $rev)
	{
		return $this->_dataDir . DIRECTORY_SEPARATOR . $page . '.' . $rev . '.rev';
	}
	
	function pageExists($page)
	{
		return file_exists($this->_pageFN($page));
	}
	
	function getPage($page)
	{
		if ($this->pageExists($page)) {
			return file_get_contents($this->_pageFN($page));
		}
		return null;
	}
	
	function savePage($page, $newText, $author='', $comment='')
	{
		$oldText = $this->getPage($page);
		if ($newText != $oldText) {
			$history = $this->getHistory($page);
			$revision = count($history);
			if (file_put_contents($this->_pageFN($page), $newText) ) {
				file_put_contents($this->_revFN($page, $revision), $newText);
				$history[] = array(
					'date'=>date('Y-m-d'),
					'time'=>date('H:i:s'),
					'author'=>$author,
					'comment'=>$comment
				);
				file_put_contents($this->_logFN($page), json_encode($history));
			}
		}
	}
	
	function getHistory($page)
	{
		if (file_exists($this->_logFN($page))) {
			return json_decode(file_get_contents($this->_logFN($page)), true);
		} else {
			return array();
		}
	}
	
	
}