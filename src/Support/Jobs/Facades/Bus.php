<?php namespace Melon\Support\Jobs\Facades;

use Illuminate\Support\Facades\Facade;

class Bus extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return \Melon\Support\Jobs\Bus::class;
	}

}