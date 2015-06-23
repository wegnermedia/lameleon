<?php namespace Melon\Support\Cache\Traits;

use Melon\Support\Cache\Contracts\CacherService;
use Melon\Support\Cache\Events\CacheCalledEvent;
use Melon\Support\Cache\Events\CacheCallingEvent;
use Closure;

trait CacheSupport {

	/**
	 * Get an item from the cache or get a default value
	 *
	 * @param          $name
	 * @param callable $callback
	 * @param null     $minutes
	 *
	 * @return mixed
	 */
	public function cache($name, Closure $callback, $minutes = null)
	{
		fire( new CacheCallingEvent($name) );

		// Prefix the name with the current client if nessesary
		if ( ! env('APP_CACHING', false) )
		{
			fire( new CacheCalledEvent($name, $minutes, false));
			return $callback();
		}

		if ( $minutes )
		{
			fire( new CacheCalledEvent($name, $minutes, true));
			return app(CacherService::class)->remember($name, $minutes, $callback);
		}

		fire( new CacheCalledEvent($name, $minutes, true));
		return app(CacherService::class)->sear($name, $callback);
	}

} 