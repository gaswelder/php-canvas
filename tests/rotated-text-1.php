<?php

use gaswelder\Canvas;

return function (Canvas $c) {
	$c->translate($c->width() / 2, $c->height() / 2);

	$c->fillStyle = 'red';
	$c->fillRect(-2, -2, 4, 4);
	$c->fillStyle = 'black';

	$diff = PI / 10;
	$angle = 0;

	for ($i = 0; $i < 20; $i++) {
		$c->fillText($angle, 50, 0);
		$c->rotate($diff);
		$angle += $diff;
	}
};
