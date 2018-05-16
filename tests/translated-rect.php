<?php

use gaswelder\Canvas;

return function (Canvas $c) {
	$c->translate(50, 50);
	$c->fillRect(0, 0, 100, 100);
};
