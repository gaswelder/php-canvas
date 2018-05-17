<?php

namespace gaswelder;

trait Paths
{
	public $fillStyle = 'black';
	public $strokeStyle = 'black';

	/*
	 * List of subpaths. Each subpath is a list of points [x, y].
	 */
	private $path = [];

	/**
	 * Discards the current list of subpaths and starts a new path.
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

	/**
	 * Closes current subpath by adding a line to its first point.
	 */
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
			$this->polyFill($points);
		}
	}

	/*
	 * Takes an array of [x, y] points and fills the shape it defines.
	 * There's no need to close the path for this function.
	 */
	private function polyFill($points)
	{
		$flatCoords = call_user_func_array('array_merge', $points);
		$r = imagefilledpolygon($this->img, $flatCoords, count($points), $this->getColor($this->fillStyle));
		$this->check($r, 'imagefilledpolygon');
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
