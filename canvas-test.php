<?php

require 'src/canvas.php';

use gaswelder\Canvas;

class tests
{
	private $set = [];

	function add($title, $func)
	{
		$this->set[] = [$title, $func];
	}

	function run($name)
	{
		foreach ($this->set as $example) {
			list($title, $func) = $example;
			if ($name && $name != $title) continue;
			error_log($title);
			$c = new Canvas(300, 300);
			$func($c);
			file_put_contents($title . '.png', $c->data());
		}
	}
}

define('PI', acos(-1));

$test = new tests();

$test->add('rotated-text-1', function (canvas $c) {
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
});

$test->add('rotated-text-2', function (canvas $c) {

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
});


$test->add('rect', function (canvas $c) {
	$c->fillRect(25, 25, 100, 100);
	$c->clearRect(45, 45, 60, 60);
	$c->strokeRect(50, 50, 50, 50);
});

// Filled triangle
$test->add('filled-triangle', function (canvas $c) {
	$c->beginPath();
	$c->moveTo(75, 50);
	$c->lineTo(100, 75);
	$c->lineTo(100, 25);
	$c->fill();
});

// Smiley
$test->add('smiley', function (canvas $c) {
	$c->beginPath();
	$c->arc(75, 75, 50, 0, PI * 2, true); // Outer circle
	$c->moveTo(110, 75);
	$c->arc(75, 75, 35, 0, PI, false);  // Mouth (clockwise)
	$c->moveTo(65, 65);
	$c->arc(60, 65, 5, 0, PI * 2, true);  // Left eye
	$c->moveTo(95, 65);
	$c->arc(90, 65, 5, 0, PI * 2, true);  // Right eye
	$c->stroke();
});

$test->add('triangles', function (canvas $c) {
	// Filled triangle
	$c->beginPath();
	$c->moveTo(25, 25);
	$c->lineTo(105, 25);
	$c->lineTo(25, 105);
	$c->fill();

    // Stroked triangle
	$c->beginPath();
	$c->moveTo(125, 125);
	$c->lineTo(125, 45);
	$c->lineTo(45, 125);
	$c->closePath();
	$c->stroke();
});

$test->add('filled-arcs', function (canvas $c) {
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
});

$test->add('skewed-rect', function (canvas $c) {
	$c->transform(1, 1, 0, 1, 0, 0);
	$c->fillRect(0, 0, 100, 100);
});

$test->add('rotated-rect', function (canvas $c) {
	$c->rotate(45 * PI / 180);
	$c->fillRect(70, 0, 100, 30);
});

$test->add('translated-rect', function (canvas $c) {
	$c->translate(50, 50);
	$c->fillRect(0, 0, 100, 100);
});

$name = isset($argv[1]) ? $argv[1] : null;
$test->run($name);
