<?php namespace Melon\Console\Creator\Traits;

trait PathsTrait
{
	/**
	 * Get the Repository Path
	 *
	 * @param      $component
	 * @param null $file
	 *
	 * @return string
	 */
	protected function repositoryPath($component, $file = null)
	{
		return app_path("{$component}/Repository/{$file}");
	}


	/**
	 * Get the Provider Path
	 *
	 * @param null $file
	 *
	 * @return string
	 */
	protected function providerPath($file = null)
	{
		return app_path("Providers") . '/' . $file;
	}


	/**
	 * Get the Seeder path.
	 *
	 * @param null $file
	 *
	 * @return string
	 */
	protected function seederPath($file = null)
	{
		return base_path("database/seeds") . '/' . $file;
	}


	/**
	 * Get the Event path.
	 *
	 * @param      $component
	 * @param null $file
	 *
	 * @return string
	 */
	protected function eventPath($component, $file = null)
	{
		return app_path("{$component}/Events/{$file}");
	}


	/**
	 * Get the component based request path
	 *
	 * @param      $component
	 * @param null $file
	 *
	 * @return string
	 */
	protected function requestPath($component, $element, $file = null)
	{
		return app_path("Http/Requests/{$element}/{$component}/{$file}");
	}


	/**
	 * Get the job path.
	 *
	 * @param      $component
	 * @param null $file
	 *
	 * @return string
	 */
	protected function jobPath($component, $file = null)
	{
		return app_path("{$component}/Jobs/{$file}");
	}

	/**
	 * Get the components presenters path.
	 *
	 * @param      $component
	 * @param null $file
	 *
	 * @return string
	 */
	protected function presenterPath($component, $file = null)
	{
		return app_path("{$component}/Presenters/Eloquent/{$file}");
	}


	/**
	 * Get the Service path, based on component.
	 *
	 * @param      $component
	 * @param null $file
	 *
	 * @return string
	 */
	protected function servicePath($component, $element, $file = null)
	{
		return app_path("{$component}/Services/{$element}/{$file}");
	}

	/**
	 * Get the Controller path, based on component.
	 *
	 * @param      $component
	 * @param null $file
	 *
	 * @return string
	 */
	protected function controllerPath( $element, $component, $file = null )
	{
		return app_path("Http/Controllers/{$element}/{$component}/{$file}");
	}

	/**
	 * Get the Controller path, based on component.
	 *
	 * @param      $component
	 * @param null $file
	 *
	 * @return string
	 */
	protected function controllerRootPath( $file = null )
	{
		return app_path("Http/Controllers/{$file}");
	}
} 