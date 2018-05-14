<?php

namespace gaswelder;

trait Shapes
{
	public $fillStyle = 'black';
	public $strokeStyle = 'black';

	/*
	 * List of subpaths. Each subpath is a list of points [x, y].
	 */
	private $path = [];

	/**
	 * Starts a new path.
	 */
	function beginPath()
	{
		$this->path = [];
	}

	/**
	 * Starts a new subpath at the given point.
	 * 
	 * @param float $x
	 * @param float $y
	 */
	function moveTo($x, $y)
	{
		list($x, $y) = $this->calc($x, $y);
		$this->path[] = [[$x, $y]];
	}

	/**
	 * Adds a line from the previous point to the given
	 *
	 * @param float $x
	 * @param float $y
	 */
	function lineTo($x, $y)
	{
		list($x, $y) = $this->calc($x, $y);
		$n = count($this->path);
		if ($n == 0) {
			throw new Exception("Empty path");
		}
		$this->path[$n - 1][] = [$x, $y];
	}

	function closePath()
	{
		$n = count($this->path);
		if ($n == 0) {
			throw new Exception("Empty path");
		}
		$begin = $this->path[$n - 1][0];
		$this->path[$n - 1][] = [$begin[0], $begin[1]];
	}

	/**
	 * Adds an arc to the path. The arc is center at (x, y).
	 *
	 * @param float $x
	 * @param float $y
	 * @param float $radius
	 * @param float $startAngle Radians
	 * @param float $endAngle Radians
	 */
	function arc($x, $y, $radius, $startAngle, $endAngle, $anticlockwise = false)
	{
		$points = $this->arcPoints([$x, $y], $radius, $startAngle, $endAngle, $anticlockwise);
		$points = array_map(function ($pos) {
			return $this->calc($pos[0], $pos[1]);
		}, $points);
		$this->path[] = $points;
	}

	/**
	 * Adds a closed rectangle to the path.
	 *
	 * @param float $x
	 * @param float $y
	 * @param float $width
	 * @param float $height
	 */
	function rect($x, $y, $width, $height)
	{
		$this->moveTo($x, $y);
		$this->lineTo($x + $width, $y);
		$this->lineTo($x + $width, $y + $height);
		$this->lineTo($x, $y + $height);
		$this->lineTo($x, $y);
	}

	function fillRect($x, $y, $width, $height)
	{
		$this->rect($x, $y, $width, $height);
		$this->fill();
	}

	function strokeRect($x, $y, $width, $height)
	{
		$this->rect($x, $y, $width, $height);
		$this->stroke();
	}

	function clearRect($x, $y, $width, $height)
	{
		$fs = $this->fillStyle;
		$this->fillStyle = 'white';
		$this->fillRect($x, $y, $width, $height);
		$this->fillStyle = $fs;
	}

	/**
	 * Draws the line currently in the buffer.
	 */
	function stroke()
	{
		foreach ($this->path as $points) {
			$this->polyLine($points);
		}
	}

	function fill()
	{
		foreach ($this->path as $points) {
			$flatCoords = call_user_func_array('array_merge', $points);
			$r = imagefilledpolygon($this->img, $flatCoords, count($points), $this->getColor($this->fillStyle));
			$this->check($r, 'imagefilledpolygon');
		}
	}

	private function arcPoints($center, $radius, $startAngle, $endAngle, $anticlockwise)
	{
		$points = [];
		$angles = [];
		if ($anticlockwise) {
			while ($startAngle <= $endAngle) {
				$startAngle += 2 * PI;
			}
			$angles = range($startAngle, $endAngle, -0.1);
		} else {
			while ($endAngle <= $startAngle) {
				$endAngle += 2 * PI;
			}
			$angles = range($startAngle, $endAngle, 0.1);
		}
		foreach ($angles as $angle) {
			$points[] = [
				$center[0] + $radius * cos($angle),
				$center[1] + $radius * sin($angle)
			];
		}
		$points[] = [
			$center[0] + $radius * cos($endAngle),
			$center[1] + $radius * sin($endAngle)
		];
		return $points;
	}

	private function polyLine($points)
	{
		$color = $this->getColor($this->strokeStyle);
		$pos = array_shift($points);
		foreach ($points as $next) {
			$r = imageline($this->img, $pos[0], $pos[1], $next[0], $next[1], $color);
			$this->check($r, 'imageline');
			$pos = $next;
		}
	}
}
