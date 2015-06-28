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
		$this->publishDatabaseFiles();
		$this->publishAppFiles();
		$this->defineModelConstants();
		$this->bootFacades();
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
	protected function publishDatabaseFiles()
	{
		$this->publishes([
			$this->root('database/migrations') => database_path('migrations'),
			$this->root('database/seeds') => database_path('seeds'),
		]);
	}

	/**
	 *
	 */
	protected function publishAppFiles()
	{
		$this->publishes([
			$this->root('app') => app_path(),
			$this->root('database/seeds') => database_path('seeds'),
		]);
	}

	/**
	 *
	 */
	protected function defineModelConstants()
	{
		foreach(config('melon.models') as $name => $class)
		{
			$key = 'MELON_MODEL_' . strtoupper($name);

			define($key, $class);
		}
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
			__DIR__ . '/Helpers/*.php',
		    __DIR__ . '/*/Helpers/*.php',
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


	/**
	 * Boot up the Melon Facades
	 */
	protected function bootFacades()
	{
		$loader = \Illuminate\Foundation\AliasLoader::getInstance();

		foreach( config('melon.facades', []) as $name => $class)
		{
			$loader->alias($name, $class);
		}
	}
}