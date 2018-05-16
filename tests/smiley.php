<?php

use gaswelder\Canvas;

return function (Canvas $c) {
	$c->beginPath();
	$c->arc(75, 75, 50, 0, PI * 2, true); // Outer circle
	$c->moveTo(110, 75);
	$c->arc(75, 75, 35, 0, PI, false);  // Mouth (clockwise)
	$c->moveTo(65, 65);
	$c->arc(60, 65, 5, 0, PI * 2, true);  // Left eye
	$c->moveTo(95, 65);
	$c->arc(90, 65, 5, 0, PI * 2, true);  // Right eye
	$c->stroke();
};
