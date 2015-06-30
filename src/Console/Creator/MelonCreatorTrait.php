<?php namespace Melon\Console\Creator;

use File;

/**
 * Class MelonCreatorTrait
 *
 * @package Melon\Console\Creator
 */
trait MelonCreatorTrait
{
	/**
	 * @param       $template
	 * @param array $arguments
	 *
	 * @return mixed|null|string
	 */
	protected function getTemplate($template, array $arguments = [])
	{
		$root = config('melon.app_name', 'App');

		$arguments = array_merge(compact('root'), $arguments);

		// Get the Template and replace params
		$template = __DIR__ . '/Stubs/' . $template . '.stub';

		if( ! file_exists($template)) return null;

		$raw = File::get($template);

		foreach($arguments as $search => $replace)
		{
			$raw = str_replace('{' . $search . '}', $replace, $raw);
		}

		return $raw;
	}

	/**
	 * @param       $template
	 * @param       $path
	 * @param array $arguments
	 *
	 * @return $this
	 */
	protected function create($template, $path, array $arguments = [])
	{
		if(file_exists($path)) return $this;

		check_directory($path);

		$content = $this->getTemplate($template, $arguments);

		File::put($path, $content);

		return $this;
	}

	/**
	 * @param      $name
	 * @param null $optional
	 *
	 * @return null
	 */
	protected function toTable($component, $name, $optional = null)
	{
		return is_null($optional) ? $component . '__' . $name : $optional;
	}

	/**
	 * @param $table
	 *
	 * @return string
	 */
	protected function toTranslationTable($table)
	{
		return $table . '_Translations';
	}


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
		return app_path("{$component}/Repository/Eloquent/{$file}");
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
	 * Get the applications root name.
	 *
	 * @return mixed
	 */
	protected function getRoot()
	{
		return config('melon.app_name', 'App');
	}

	/**
	 * @param array $lines
	 * @param       $content
	 *
	 * @return int
	 */
	protected function contentAlreadyExists($haystack, $pattern, $regex = false)
	{
		if ( is_array($haystack) )
			$haystack = implode(' ', $haystack);

		$pattern = $regex ? $pattern : "#".preg_quote($pattern)."#u";

		return preg_match($pattern, $haystack);
	}

	/**
	 * @param $file
	 * @param $content
	 * @param $indicator
	 * @param $pattern
	 *
	 * @return $this
	 */
	public function appendContentToFile($file, $content, $indicator, $pattern, $regex = false )
	{
		$lines = file($file);

		if($this->contentAlreadyExists($lines, $indicator))
			return $this;

		array_insert_at_pattern($lines, [ $pattern => $content], $regex);

		File::delete($file);
		File::put($file, implode("", $lines));

		return $this;
	}

	/**
	 * Insert content into a file between a pattern
	 *
	 * @param      $path
	 * @param      $content
	 * @param      $after
	 * @param      $before
	 * @param null $indicator
	 *
	 * @return $this
	 */
	protected function insertContentIntoFile($path, $content, $after, $before, $indicator = null, $regex = false)
	{
		$lines = file($path);

		$indicator = is_null($indicator) ? $content : $indicator;

		if( $this->contentAlreadyExists($lines, $indicator) )
			return $this;

		array_insert_into($lines, $content, $after, $before, $regex);

		File::delete($path);
		File::put($path, implode("", $lines));

		return $this;
	}

} 