<?php namespace Melon\Presenting;

/**
 * Class Presenter
 *
 * @package Bake\Foundation\Presenting
 */
abstract class Presenter {

	/**
	 * @var
	 */
	protected $entity;


	/**
	 * @param $entity
	 */
	public function __construct($entity)
	{
		$this->entity = $entity;
	}


	/**
	 * @param      $key
	 * @param null $default
	 *
	 * @return mixed
	 */
	public function get($key, $default = null)
	{
		// Check for a Presenter Method first
		if ( method_exists($this, $key) )
			return $this->$key($default);

		// Ok, now try to access the entities attribute
		return object_get($this->entity, $key, $default);
	}


	/**
	 * @param $name
	 * @param $arguments
	 *
	 * @return mixed
	 */
	function __call($name, $arguments)
	{
		return $this->get($name);
	}

	/**
	 * @param $name
	 *
	 * @return mixed
	 */
	function __get($name)
	{
		return $this->get($name);
	}


}