<?php namespace Melon\Current;

use Melon\Current\Events\CurrentEntityDetectedEvent;
use Melon\Current\Exceptions\NotDetectableException;


/**
 * Class Detector
 *
 * @package Bake\Support\System\Application
 */
abstract class Detector {

	/**
	 * The Result of the Detector
	 *
	 * @var null
	 */
	public $detected = null;


	/**
	 * @var
	 */
	public $detector;

	/**
	 * This is what happens, wenn the detection passes
	 *
	 * @param $lookup
	 *
	 * @return mixed
	 */
	abstract protected function handleDetectionComplete($lookup);


	/**
	 * Process the Detection
	 *
	 * @throws \Melon\Current\Exceptions\NotDetectableException
	 * @return mixed|null
	 */
	public function run($lookup = null)
    {
	    $name = $this->getName();
	    $key = $name . '_detection';

       debugger()->start($key, "[{$name}] Detection.");



	    foreach( $this->getLookups($lookup) as $lookup )
        {
            $method = 'try' . $lookup;

            if( ! method_exists( $this, $method ) ) continue;

            if( $this->detected = $this->$method() )
            {
	            debugger()->stop($key);

	            fire(new CurrentEntityDetectedEvent($name, $this->detected, $lookup));

	            return $this->handleDetectionComplete($lookup);
            }
        }

       debugger()->stop($key);

        throw new NotDetectableException($this->getName());
    }


	/**
	 * Get the Lookup Methods for this detection,
	 * whether the default ones in the class itself OR given ones :-)
	 *
	 *
	 * @param null $lookup
	 *
	 * @return array|null
	 */
	protected function getLookup($lookup = null)
	{
		if ( is_null($lookup) )
			return $this->lookups;

		if ( is_array($lookup) )
			return $lookup;

		if ( is_string($lookup))
			return [$lookup];

		return [];
	}

	/**
	 * @return mixed
	 */
	protected function getName()
	{
		return preg_replace("/^DetectCurrent/u", "", get_classname($this->detector));
	}

} 