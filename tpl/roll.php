<html>
<head>
<title>ROLL</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>
<?php foreach ($listing as $name=>$item): ?>
<h3>[<?php echo $name; ?>]</h3>
<p><?php if (isset($item["info"])) echo $item["info"]; ?></p>
<?php
if (isset($item["tags"])){
	echo 'tags: ';
	foreach ($item["tags"] as $tag) {
		echo '<a href="'. $linkURL .'?q=@' . $tag . '">' . $tag .'</a> ';
	}
	echo '<br />';
}
?>
<form method="POST" action="<?php echo $linkURL; ?>/down">
	<input type="hidden" name="package" value="<?php echo $name; ?>">
	<input type="submit" value="Download/Upgrade">
</form>
<?php endforeach;?>
</body>
</html>