<?php
class brick_roll
{
	private $conf=array();
	private $basePath=null;

	function __construct($rollFile)
	{
		$conf = parse_ini_file($rollFile, TRUE);
		$this->basePath = dirname($rollFile);
		foreach ($conf as $name=>$item) {
			if (isset($conf[$name]["tags"])) {
				$conf[$name]["tags"] = explode(" ", $conf[$name]["tags"]);
			}
		}
		$this->conf = $conf;
	}

	function query($query)
	{
		$listed = array();
		if ($query==".") {
			$listed = $this->conf;
		} elseif (substr($query, 0, 1)=="@") {
			$tag = substr($query, 1);
			foreach ($this->conf as $name=>$item) {
				if ((isset($item["tags"])) and in_array($tag, $item["tags"])) {
					$listed[$name] = $item;
				}
			}
		} else {
			$toBeListed = explode(",", $query);
			foreach ($this->conf as $name=>$item) {
				if (in_array($name, $toBeListed))
				$listed[$name] = $item;
			}
		}
		ksort($listed);
		return $listed;
	}
	
	function down($query)
	{
		$listed = $this->query($query);
		foreach ($listed as $conf) {
			mkdir($this->basePath . DIRECTORY_SEPARATOR . $conf["local"]);
		}
	}
}