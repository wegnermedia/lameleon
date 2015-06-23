<?php namespace Melon\Current;

use Melon\Current\Contracts\CurrentableInterface;
use Melon\Current\Exceptions\NotDetectableException;

/**
 * Class Currentable
 */
abstract class Currentable implements CurrentableInterface {

	/**
	 * The Current Entity
	 * @var
	 */
	protected $current;


	/**
	 * Get the Detector
	 *
	 * @return Detector
	 */
	abstract protected function getDetector();


	/**
	 * Handler for the Not detectable exception
	 *
	 * @return mixed
	 */
	abstract protected function handleNotDetectableException();

	/**
	 * Initial Boot up to detect the current entity
	 *
	 * @return $this
	 */
	public function boot()
	{
		$this->current();

		return $this;
	}

	/**
	 * Get the Current Entity or a property out of it
	 *
	 * @return mixed
	 */
	public function current($key = null, $default = null)
	{
		if ( ! $this->current )
			$this->current = $this->detectCurrentEntity();

		if ( is_null($key) )
			return $this->current;

		return $this->current->property($key, $default);
	}


	/**
	 * Try to detect the current entity and handle Errors for yourself
	 *
	 * @return mixed
	 */
	protected function detectCurrentEntity()
	{
		try
		{
			return $this->getDetector()->run();
		}
		catch(NotDetectableException $e)
		{
			return $this->handleNotDetectableException();
		}
	}
}