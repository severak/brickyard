<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo $title; ?></title>
	<link href="/static/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<style>body {padding-top: 40px; padding-bottom: 40px;}</style>
</head>
<body id="top">
	<div class="navbar navbar-fixed-top navbar-inverse">
		<div class="navbar-inner">
			<div class="container">
				<?php
				if (isset($brand)) {
					if (isset($brandURL)) {
						echo '<a href="' . $brandURL . '" class="brand">' . $brand . '</a>';
					} else {
						echo '<span class="brand">' . $brand . '</span>';
					}
				}
				if (isset($menu)) {
					echo '<ul class="nav">';
					foreach ($menu as $url=>$item) {
						echo '<li><a href="'. $url .'">' . $item . '</a></li>';
					}
					echo '</ul>';
				}
				if (isset($otherNavbarContent)) {
					echo $otherNavbarContent;
				}
				?>
			</div>
		</div>
	</div>
	<div class="container">
		<?php echo $main; ?>
	</div>
</body>
</html>