<?php

use gaswelder\Canvas;

return function (Canvas $c) {
	// Filled triangle
	$c->beginPath();
	$c->moveTo(25, 25);
	$c->lineTo(105, 25);
	$c->lineTo(25, 105);
	$c->fill();

	// Stroked triangle
	$c->beginPath();
	$c->moveTo(125, 125);
	$c->lineTo(125, 45);
	$c->lineTo(45, 125);
	$c->closePath();
	$c->stroke();
};
