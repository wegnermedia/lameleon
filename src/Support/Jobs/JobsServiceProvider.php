<?php namespace Melon\Support\Jobs;

use Illuminate\Support\ServiceProvider;
use Melon\Support\Jobs\Contracts\BusService;

class JobsServiceProvider extends ServiceProvider {

	/**
	 * Boot up after Registration
	 */
	public function boot()
	{

	}

	/**
	 * Register Resources and Services
	 */
	public function register()
	{
		$this->app->singleton(BusService::class, function($app){
			return $app->make(Bus::class);
		});
	}
}