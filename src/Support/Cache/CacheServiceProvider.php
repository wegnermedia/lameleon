<?php namespace Melon\Support\Cache;

use Melon\Support\Cache\Contracts\CacherService;
use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider {

	/**
	 * Boot up after Registration
	 */
	public function boot()
	{

	} // /boot

	/**
	 * Register Resources and Services
	 */
	public function register()
	{

		$this->app->singleton(CacherService::class, function($app){
			return $app->make(Cacher::class);
		});

	} // /register
}