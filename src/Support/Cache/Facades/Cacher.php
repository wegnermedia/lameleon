<?php namespace Melon\Cache\Facades;

use Melon\Cache\Contracts\CacherService;
use Illuminate\Support\Facades\Facade;

class Cacher extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return CacherService::class;
	}

}