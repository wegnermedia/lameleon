<?php namespace Melon\Current\Contracts;

interface CurrentableInterface {

	/**
	 * Return the Current detected Model
	 *
	 * @param null $key
	 * @param null $default
	 *
	 * @return mixed
	 */
	public function current($key = null, $default = null);

} 