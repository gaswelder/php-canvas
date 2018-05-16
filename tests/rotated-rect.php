<?php

use gaswelder\Canvas;

return function (Canvas $c) {
	$c->rotate(45 * PI / 180);
	$c->fillRect(70, 0, 100, 30);
};
