<?php

require 'src/canvas.php';

use gaswelder\Canvas;

define('PI', acos(-1));

function d($var)
{
	echo json_encode($var, JSON_PRETTY_PRINT), "\n";
}

$mask = isset($argv[1]) ? $argv[1] : '';
foreach (glob("tests/*$mask*.php") as $path) {
	$func = require $path;
	$title = str_replace('.php', '', basename($path));
	error_log($title);
	$c = new Canvas(300, 300);
	$func($c);
	file_put_contents($title . '.png', $c->data());
}
