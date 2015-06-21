<?php namespace Wegnermedia\Rocket;

use Illuminate\Support\ServiceProvider;

/**
 * Class RocketServiceProvider
 *
 * @package Wegnermedia\Rocket
 */
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
		$this->requireHelperFiles();
		$this->registerConsoleCommands();
		$this->registerServiceProviders();
	}


	/**
	 * @param null $path
	 *
	 * @return string
	 */
	protected function root($path = null)
	{
		return __DIR__ . '/../../../'.$path;
	}


	/**
	 *
	 */
	protected function publishConfigFiles()
	{
		$this->publishes([
			$this->root('config/rocket.php') => config_path('rocket.php'),
		]);
	}


	/**
	 *
	 */
	protected function mergeConfigFiles()
	{
		$this->mergeConfigFrom($this->root('config/rocket.php'), 'rocket');
	}

	/**
	 * Require Helper Files
	 *
	 * @return $this
	 */
	protected function requireHelperFiles()
	{
		$defaults = [
			__DIR__ . '/Helpers/*.php',
		];

		$paths = array_unique(array_merge($defaults, config('rocket.helpers', [])));

		foreach( $paths as $path )
		{
			$files = glob($path);

			foreach($files as $file)
			{
				require $file;
			}
		}

		return $this;
	}

	/**
	 * Register Console Commands
	 */
	protected function registerConsoleCommands()
	{
		foreach( config('rocket.commands', []) as $name => $class)
		{
			$this->app[$name] = $this->app->share(
				function ($app) use ($class) {
					return $app[$class];
				}
			);

			$this->commands([$name]);
		}

		return $this;
	}

	/**
	 * Register all Service Providers
	 */
	protected function registerServiceProviders()
	{
		foreach(config('rocket.providers', []) as $provider)
		{
			$this->app->register($provider);
		}

		return $this;
	}
}