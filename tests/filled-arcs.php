<?php

use gaswelder\Canvas;

return function (Canvas $c) {
	for ($i = 0; $i < 4; $i++) {
		for ($j = 0; $j < 3; $j++) {

			$x = 25 + $j * 50; // x coordinate
			$y = 25 + $i * 50; // y coordinate
			$radius = 20; // Arc radius
			$startAngle = 0; // Starting point on circle
			$endAngle = PI + (PI * $j) / 2; // End point on circle
			$anticlockwise = $i % 2 !== 0; // clockwise or anticlockwise

			$c->beginPath();
			$c->arc($x, $y, $radius, $startAngle, $endAngle, $anticlockwise);

			if ($i > 1) {
				$c->fill();
			} else {
				$c->stroke();
			}
		}
	}
};
