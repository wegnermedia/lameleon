<?php namespace Melon\Support\Debug\Facades;

use Melon\Support\Debug\Contracts\DebuggerService;
use Illuminate\Support\Facades\Facade;

class Debugger extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return DebuggerService::class;
	}

}