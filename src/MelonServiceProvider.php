<?php namespace Melon;

use Illuminate\Support\ServiceProvider;

/**
 * Class MelonServiceProvider
 *
 * @package Melon
 */
class MelonServiceProvider extends ServiceProvider
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
			$this->root('config/melon.php') => config_path('melon.php'),
		]);
	}


	/**
	 *
	 */
	protected function mergeConfigFiles()
	{
		$this->mergeConfigFrom($this->root('config/melon.php'), 'melon');
	}

	/**
	 * Require Helper Files
	 *
	 * @return $this
	 */
	protected function requireHelperFiles()
	{
		$defaults = [
			__DIR__ . '/Support/Helpers/*.php',
		    __DIR__ . '/Support/*/Helpers/*.php',
		];

		$paths = array_unique(array_merge($defaults, config('melon.helpers', [])));

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
		foreach( config('melon.commands', []) as $name => $class)
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
		foreach(config('melon.providers', []) as $provider)
		{
			$this->app->register($provider);
		}

		return $this;
	}
}