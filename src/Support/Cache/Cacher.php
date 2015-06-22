<?php namespace Melon\Support\Cache;

use Melon\Support\Cache\Contracts\CacherService;
use Closure;
use Illuminate\Cache\Repository as LaravelCache;
use Illuminate\Contracts\Cache\Repository;

class Cacher implements CacherService, Repository {

	protected $enabled = false;

	/**
	 * @var \Illuminate\Cache\Repository
	 */
	private $cache;


	function __construct(LaravelCache $cache)
	{
		$this->enabled = config('melon.caching', false);
		$this->cache = $cache;
	}


	/**
	 * Determine if an item exists in the cache.
	 *
	 * @param  string $key
	 *
	 * @return bool
	 */
	public function has($key)
	{
		return $this->enabled ? $this->cache->has($key) : false;
	}


	/**
	 * Retrieve an item from the cache and delete it.
	 *
	 * @param  string $key
	 * @param  mixed  $default
	 *
	 * @return mixed
	 */
	public function pull($key, $default = null)
	{
		return $this->enabled ? $this->cache->pull($key, $default) : $default;
	}


	/**
	 * Store an item in the cache.
	 *
	 * @param  string        $key
	 * @param  mixed         $value
	 * @param  \DateTime|int $minutes
	 *
	 * @return void
	 */
	public function put($key, $value, $minutes)
	{
		if($this->enabled) $this->cache->put($key, $value, $minutes);
	}


	/**
	 * Store an item in the cache if the key does not exist.
	 *
	 * @param  string        $key
	 * @param  mixed         $value
	 * @param  \DateTime|int $minutes
	 *
	 * @return bool
	 */
	public function add($key, $value, $minutes)
	{
		return $this->enabled ? $this->cache->add($key, $value, $minutes) : false;
	}


	/**
	 * Store an item in the cache indefinitely.
	 *
	 * @param  string $key
	 * @param  mixed  $value
	 *
	 * @return void
	 */
	public function forever($key, $value)
	{
		if($this->enabled) $this->cache->forever($key, $value);
	}


	/**
	 * Get an item from the cache, or store the default value.
	 *
	 * @param  string        $key
	 * @param  \DateTime|int $minutes
	 * @param  \Closure      $callback
	 *
	 * @return mixed
	 */
	public function remember($key, $minutes, Closure $callback)
	{
		if($this->enabled) return $this->cache->remember($key, $minutes, $callback);

		return $callback;
	}


	/**
	 * Get an item from the cache, or store the default value forever.
	 *
	 * @param  string   $key
	 * @param  \Closure $callback
	 *
	 * @return mixed
	 */
	public function sear($key, Closure $callback)
	{
		if($this->enabled) return $this->cache->sear($key, $callback);

		return $callback;
	}


	/**
	 * Get an item from the cache, or store the default value forever.
	 *
	 * @param  string   $key
	 * @param  \Closure $callback
	 *
	 * @return mixed
	 */
	public function rememberForever($key, Closure $callback)
	{
		if($this->enabled) return $this->cache->rememberForever($key, $callback);

		return $callback;
	}


	/**
	 * Remove an item from the cache.
	 *
	 * @param  string $key
	 *
	 * @return bool
	 */
	public function forget($key)
	{
		return $this->enabled ? $this->cache->forget($key) : false;
	}


	/**
	 * Retrieve an item from the cache by key.
	 *
	 * @param  string $key
	 * @param  mixed  $default
	 *
	 * @return mixed
	 */
	public function get($key, $default = null)
	{
		return $this->enabled ? $this->cache->get($key, $default) : $default;
	}
}