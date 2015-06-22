<?php namespace Melon\Support\Debug;

use Melon\Support\Debug\Contracts\DebuggerService;
use Illuminate\Support\ServiceProvider;

class DebugServiceProvider extends ServiceProvider {

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

		$this->app->singleton(DebuggerService::class, function($app){
			return $app->make(DebugbarDebugger::class);
		});

	} // /register
}