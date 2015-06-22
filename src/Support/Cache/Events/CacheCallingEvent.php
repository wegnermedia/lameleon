<?php namespace Melon\Support\Cache\Events;

use Melon\Support\Events\Event;

class CacheCallingEvent extends Event {

	/**
	 * @var
	 */
	public $name;

	function __construct($name)
	{
		$this->name = $name;
	}
}