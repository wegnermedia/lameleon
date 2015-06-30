<?php

if ( ! function_exists('sites_path') ) {

	/**
	 * @param string $path
	 *
	 * @return string
	 */
	function sites_path($path = '')
	{
		return base_path('resources/sites/') . trim($path, '/');
	}
}

if ( ! function_exists('path_to_namespace') ) {

	/**
	 * convert a path to an app_path based namespace.
	 *
	 * @param $path
	 *
	 * @return mixed
	 */
	function path_to_namespace($path)
    {
	    $stripped = preg_replace("#".app_path()."/#uism", config('melon.app_name', 'App') . "\\", dirname($path));

	    return str_replace('/', '\\', $stripped);
    }
}

if ( ! function_exists('namespace_to_path') ) {

	/**
	 * Convert a namespace to an app_path based path.
	 *
	 * @param      $namespace
	 * @param null $file
	 *
	 * @return string
	 */
	function namespace_to_path($namespace, $file = null)
    {
	    $prefixed = preg_replace("#^" . $this->getRoot() . "#uism", app_path(), $namespace);

	    return trim(str_replace('\\', '/', $prefixed) . '/' . $file, '/');
    }
}