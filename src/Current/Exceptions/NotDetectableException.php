<?php namespace Melon\Current\Exceptions;

class NotDetectableException extends \Exception {

	/**
	 * @var string
	 */
	public $type;

	function __construct($type)
	{
		$this->type = $type;

		parent::__construct("[$type] was not detectable.");
	}
}