<?php namespace Lameleon;

use Illuminate\Support\ServiceProvider;

/**
 * Class LameleonServiceProvider
 *
 * @package Lameleon
 */
class LameleonServiceProvider extends ServiceProvider
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
		return __DIR__ . '/../'.$path;
	}


	/**
	 *
	 */
	protected function publishConfigFiles()
	{
		$this->publishes([
			$this->root('config/lameleon.php') => config_path('lameleon.php'),
		]);
	}


	/**
	 *
	 */
	protected function mergeConfigFiles()
	{
		$this->mergeConfigFrom($this->root('config/lameleon.php'), 'lameleon');
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

		$paths = array_unique(array_merge($defaults, config('lameleon.helpers', [])));

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
		foreach( config('lameleon.commands', []) as $name => $class)
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
		foreach(config('lameleon.providers', []) as $provider)
		{
			$this->app->register($provider);
		}

		return $this;
	}
}