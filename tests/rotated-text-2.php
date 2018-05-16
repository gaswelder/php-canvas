<?php

use gaswelder\Canvas;

return function (Canvas $c) {
	$c->strokeRect(50, 50, 200, 200);
	$c->fillText('top left', 50, 50);

	$c->textAlign = 'right';
	$c->fillText('top right', 250, 50);

	$c->rotate(-PI / 2);
	$c->textAlign = 'left';
	$c->fillText('left bottom', -250, 50);

	$c->textAlign = 'right';
	$c->fillText('left top', -50, 50);

	$c->rotate(PI);
	$c->textAlign = 'right';
	$c->fillText('right bottom', 250, -250);

	$c->textAlign = 'left';
	$c->fillText('right top', 50, -250);

	$c->rotate(-PI / 2);
	$c->textAlign = 'center';

	$c->textBaseline = 'middle';
	$c->fillText('center', 150, 150);

	$c->textBaseline = 'top';
	$c->textAlign = 'left';
	$c->fillText('bottom left', 50, 250);
	$c->textAlign = 'right';
	$c->fillText('bottom right', 250, 250);
};
