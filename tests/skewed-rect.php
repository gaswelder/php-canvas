<?php

use gaswelder\Canvas;

return function (Canvas $c) {
	$c->transform(1, 1, 0, 1, 0, 0);
	$c->fillRect(0, 0, 100, 100);
};
