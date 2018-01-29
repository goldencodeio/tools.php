<?php

namespace goldencode\Helpers;

class Tools
{
	/**
	 * Convert a multi-dimensional array into a single-dimensional array.
	 * @link https://davidwalsh.name/flatten-nested-arrays-php
	 * @param array $array The multi-dimensional array.
	 * @param array $return
	 * @return array
	 */
	public static function array_flatten(array $array, $return = []) {
		for ($x = 0; $x <= count($array); $x++) {
			if (is_array($array[$x])) {
				$return = self::array_flatten($array[$x], $return);
			} else if (isset($array[$x])) {
				$return[] = $array[$x];
			}
		}
		return $return;
	}
}
