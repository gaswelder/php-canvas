<?php

namespace gaswelder;

require __DIR__ . '/canvas-a.php';
require __DIR__ . '/canvas-b.php';
require __DIR__ . '/canvas-shapes.php';

class Canvas extends canvas_shapes
{
	/**
	 * @var string
	 * Horizontal alignment of rendered text: 'start', 'center', 'right', 'end'.
	 */
	public $textAlign = 'start';

	/**
	 * @var string
	 * Vertical alignment of rendered text: 'top', 'middle', 'bottom'.
	 */
	public $textBaseline = 'alphabetic';

	public $font = '10px sans-serif';

	/**
	 * Renders filled text at the given position.
	 *
	 * @param string $text
	 * @param float $x
	 * @param float $y
	 */
	function fillText($text, $x, $y)
	{
		list($width, $height) = $this->label_size($text);

		$xpos = [
			'left' => 0,
			'start' => 0,
			'center' => $width / 2,
			'right' => $width,
			'end' => $width
		];
		$ypos = [
			'top' => -$height,
			'hanging' => -$height,
			'middle' => -$height / 2,
			'bottom' => 0,
			'alphabetic' => 0,
			'ideographic' => 0
		];

		if (!isset($xpos[$this->textAlign])) {
			throw new Exception("Unknown textAlign value: " . $this->textAlign);
		}
		if (!isset($ypos[$this->textBaseline])) {
			throw new Exception("Unknown textBaseline value: " . $this->textBaseline);
		}
		$x -= $xpos[$this->textAlign];
		$y -= $ypos[$this->textBaseline];

		$a = $this->matrix[0][0];
		$b = $this->matrix[1][0];
		$angle = -atan2($b, $a) / PI * 180;

		list($x, $y) = $this->calc($x, $y);
		list($fontSize, $fontFile) = explode(' ', $this->font);
		$fontSize = str_replace('px', '', $fontSize);
		$color = $this->getColor($this->fillStyle);
		$r = imagettftext($this->img, $fontSize, $angle, $x, $y, $color, $fontFile, $text);
		$this->check($r, 'imagettftext');
		return $r;
	}

	/*
	 * Returns the size of the text's bounding rectangle.
	 */
	private function label_size($text)
	{
		list($fontSize, $fontFile) = explode(' ', $this->font);
		$fontSize = str_replace('px', '', $fontSize);

		// Since we need to measure only the text itself regardless of the angle,
		// we set angle to zero here.
		$angle = 0;
		$r = imagettfbbox($fontSize, $angle, $fontFile, $text);

		$i = 0;
		$pos = [
			'lowerLeft' => [$r[$i++], $r[$i++]],
			'lowerRight' => [$r[$i++], $r[$i++]],
			'upperRight' => [$r[$i++], $r[$i++]],
			'upperLeft' => [$r[$i++], $r[$i++]],
		];

		$width = abs($pos['lowerRight'][0] - $pos['lowerLeft'][0]);
		// It's unclear what their problem is, but "upper" actually "lower",
		// so we get negative height if we follow the documentaion.
		//$height = abs($pos['upperLeft'][1] - $pos['lowerLeft'][1]);

		return [$width, $fontSize];
	}
}