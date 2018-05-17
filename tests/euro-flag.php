<?php

use gaswelder\Canvas;

$star = function ($canvas, $x, $y) {
	$r1 = 10;
	$r2 = $r1 * 0.382;

	$points = [];
	for ($i = 0; $i < 10; $i++) {
		$angle = $i * (2 * PI) / 10;
		$r = $i % 2 == 0 ? $r1 : $r2;
		$points[] = [
			$x + $r * sin($angle),
			$y - $r * cos($angle)
		];
	}

	$canvas->moveTo($points[0][0], $points[0][1]);
	foreach ($points as $p) {
		$canvas->lineTo($p[0], $p[1]);
	}
	$canvas->closePath();
	$canvas->fillStyle = 'rgb(255, 204, 0)';
	$canvas->fill();
};

return function (Canvas $canvas) use ($star) {
	// 270 x 180
	$canvas->fillStyle = 'rgb(0, 51, 153)';
	$canvas->fillRect(0, 0, 270, 180);
	$r = 60;

	for ($i = 0; $i < 12; $i++) {
		$angle = 2 * PI / 12 * $i;
		$star($canvas, 135 + $r * sin($angle), 90 + $r * cos($angle));
	}
};
