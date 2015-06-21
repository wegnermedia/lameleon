<?php

if ( ! function_exists('attributes') ) {

	/**
	 * @param array $attributes
	 *
	 * @return string
	 */
	function attributes($attributes = [])
	{
		array_walk($attributes, function(&$a, $b) {
			return $a = $b . '="' . $a . '"';
		});

		return implode(' ', $attributes);
	}
}