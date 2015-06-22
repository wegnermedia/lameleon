<?php namespace Melon\Support\Presenting;

use Melon\Support\Presenting\Exceptions\PresenterNotFoundException;

/**
 * Class PresentableTrait
 */
trait PresentableTrait {

	/**
	 * @var Presenter
	 */
	protected $presenterInstance = null;


	/**
	 * @param null $key
	 * @param null $default
	 *
	 * @return null
	 * @throws PresenterNotFoundException
	 */
	public function present($key = null, $default = null )
	{
		if ( ! $this->presenterInstance )
			$this->initializePresenterInstance();

		if ( $key )
			return $this->presenterInstance->get($key, $default);

		return $this->presenterInstance;
	}


	/**
	 * @return bool
	 * @throws PresenterNotFoundException
	 */
	protected function initializePresenterInstance()
	{
		// Dedicated named Presenter
		if ( property_exists($this, 'presenter') )
		{
			if ( $this->newPresenterInstance($this->presenter) )
				return true;
		}

		// Try the default Model Presenter
		$presenter = str_replace('Repository', 'Presenters', get_called_class()).'Presenter';

		if ( $this->newPresenterInstance($presenter) )
			return true;

		// Whoops no presenter exists
		throw new PresenterNotFoundException(get_called_class());
	}


	/**
	 * @param $presenter
	 *
	 * @return bool
	 */
	protected function newPresenterInstance($presenter)
	{
		if ( ! class_exists($presenter) )
			return false;

		$this->presenterInstance = new $presenter($this);

		return true;
	}

} 