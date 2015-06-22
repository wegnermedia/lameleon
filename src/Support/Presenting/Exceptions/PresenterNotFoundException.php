<?php namespace Melon\Support\Presenting\Exceptions;

use Exception;

class PresenterNotFoundException extends Exception {

	/**
	 * @var string
	 */
	public $class;

	function __construct($class)
	{
		parent::__construct("Presenter for [$class] not found.");

		$this->class = $class;
	}
}