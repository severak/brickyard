<?php
class brick_markdown_wrapper
{
	function parse($markdown)
	{
		include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'markdown.php';
		return Markdown($markdown);
	}
}