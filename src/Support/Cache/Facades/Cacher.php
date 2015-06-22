<?php namespace Melon\Support\Cache\Facades;

use Melon\Support\Cache\Contracts\CacherService;
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