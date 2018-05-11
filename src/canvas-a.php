<?php

namespace gaswelder;

abstract class canvas_a
{
	protected $img;
	private $width;
	private $height;
	private $colors = [];

	function __construct($width, $height)
	{
		$this->width = $width;
		$this->height = $height;
		$this->img = $this->check(imagecreate($width, $height), 'imagecreate');

		$white = $this->getColor("#eeeeee");
		$this->check(imagefill($this->img, 1, 1, $white), 'imagefill');
	}

	function __destruct()
	{
		imagedestroy($this->img);
	}

	function width()
	{
		return $this->width;
	}

	function height()
	{
		return $this->height;
	}

	protected function check($result, $name)
	{
		if ($result === false) {
			throw new Exception("$name call failed");
		}
		return $result;
	}

	function save($path)
	{
		$this->check(imagepng($this->img, $path, 9), 'imagepng');
	}

	function data()
	{
		ob_start();
		$this->check(imagepng($this->img), 'imagepng');
		return ob_get_clean();
	}

	function paste(canvas $c, $x, $y)
	{
		$this->check(imagecopy($this->img, $c->img, $x, $y, 0, 0, $c->width, $c->height), 'imagecopy');
	}

	/*
	 * Returns a color index for the GD library.
	 * $spec is in format #rrggbb, where rr, gg, and bb are hexademical
	 * numbers from 00 to ff. Case doesn't matter. The short form #rgb,
	 * equivalent to #rrggbb: #abc = #aabbcc, is also accepted.
	 */
	protected function getColor($spec)
	{
		// If a name is given, convert it to a hex. code.
		if ($spec[0] != '#') {
			$spec = $this->namedColor($spec);
		}
		if (!isset($this->colors[$spec])) {
			$this->colors[$spec] = $this->hex_color($spec);
		}
		return $this->colors[$spec];
	}

	private function hex_color($spec)
	{
		$spec = substr($spec, 1);

		$n = strlen($spec);
		assert($n == 6 || $n == 3);

		if ($n == 3) {
			$spec = "$spec[0]$spec[0]$spec[1]$spec[1]$spec[2]$spec[2]";
		}
		list($r, $g, $b) = array_map('hexdec', str_split($spec, 2));
		$color = imagecolorallocate($this->img, $r, $g, $b);
		$this->check($color, 'imagecolorallocate');
		return $color;
	}

	private function namedColor($name)
	{
		static $map = array(
			'black' => '#000000',
			'white' => '#ffffff',
			'gray' => '#999999',
			'red' => '#ff0000',
			'green' => '#00ff00',
			'blue' => '#0000ff'
		);
		if (!isset($map[$name])) {
			throw new Exception("Unknown color name: '$name'");
		}
		return $map[$name];
	}
}
