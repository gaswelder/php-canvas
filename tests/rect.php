<?php

use gaswelder\Canvas;

return function (Canvas $c) {
	$c->fillRect(25, 25, 100, 100);
	$c->clearRect(45, 45, 60, 60);
	$c->strokeRect(50, 50, 50, 50);
};
