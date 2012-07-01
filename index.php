<?php
require "brickyard.php";
$framework=new brickyard();
$framework->init();
$framework->debug=true;
$framework->controller="wiki";
$framework->router->base_url="/b/index.php/";
$framework->run();
var_dump($framework->errors);