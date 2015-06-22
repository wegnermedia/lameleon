<?php namespace Melon\Cache\Events;

use Melon\Events\Event;

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