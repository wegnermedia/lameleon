<?php namespace Wegnermedia\Rocket;

use Illuminate\Support\ServiceProvider;

class RocketServiceProvider extends ServiceProvider
{

	/**
	 * Boot up after Registration
	 */
	public function boot()
	{
		$this->publishConfigFiles();
	}

	/**
	 * Register Resources and Services
	 */
	public function register()
	{
		$this->mergeConfigFiles();
	}

	protected function root($path = null)
	{
		return __DIR__ . '/../../../'.$path;
	}

	protected function publishConfigFiles()
	{
		$this->publishes([
			$this->root('config/rocket.php') => config_path('rocket.php'),
		]);
	}

	protected function mergeConfigFiles()
	{
		$this->mergeConfigFrom($this->root('config/rocket.php'), 'rocket');
	}
}