<?php

use gaswelder\Canvas;

return function (Canvas $c) {
	$c->beginPath();
	$c->moveTo(75, 50);
	$c->lineTo(100, 75);
	$c->lineTo(100, 25);
	$c->fill();
};
