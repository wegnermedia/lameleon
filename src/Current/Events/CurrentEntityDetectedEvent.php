<?php namespace Melon\Current\Events;

use Melon\Events\Event;

class CurrentEntityDetectedEvent extends Event {

	/**
	 * @var string
	 */
	public $lookup;

	/**
	 * @var
	 */
	public $type;

	/**
	 * @var
	 */
	public $entity;


	/**
	 * @param $type
	 * @param $entity
	 * @param $lookup
	 */
	function __construct($type, $entity, $lookup)
	{
		$this->lookup = $lookup;
		$this->type = $type;
		$this->entity = $entity;
	}
}