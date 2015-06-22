<?php namespace Melon\Support\Current;

use Melon\Support\Current\Events\CurrentEntityDetectedEvent;
use Melon\Support\Current\Exceptions\NotDetectableException;


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
	 * @return mixed|null
	 */
	public function run()
    {
	    $name = $this->getName();
	    $key = $name . '_detection';

       debugger()->start($key, "[{$name}] Detection.");

	    foreach( $this->detector->lookups as $lookup )
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
	 * @return mixed
	 */
	protected function getName()
	{
		return preg_replace("/^DetectCurrent/u", "", get_classname($this->detector));
	}

} 