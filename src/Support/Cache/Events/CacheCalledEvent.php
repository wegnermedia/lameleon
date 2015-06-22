<?php namespace Melon\Support\Cache\Events;

use Melon\Support\Events\Event;

class CacheCalledEvent extends Event {

	/**
	 * @var
	 */
	public $name;

	/**
	 * @var
	 */
	public $minutes;

	/**
	 * @var
	 */
	public $active;


	function __construct($name, $minutes, $active)
	{
		$this->name = $name;
		$this->minutes = $minutes;
		$this->active = $active;
	}
}