<?php namespace Melon\Support\Log;

use Illuminate\Support\ServiceProvider;
use Melon\Support\Log\Contracts\LoggerService;

class LogServiceProvider extends ServiceProvider {

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

		$this->app->singleton(LoggerService::class, function($app){
			return $app->make(Logger::class);
		});

	} // /register
}