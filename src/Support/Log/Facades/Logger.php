<?php namespace Melon\Support\Log\Facades;

use Illuminate\Support\Facades\Facade;
use Melon\Support\Log\Contracts\LoggerService;

class Logger extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return LoggerService::class;
	}

}