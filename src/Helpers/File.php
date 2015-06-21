<?php

if ( ! function_exists('check_directory'))
{
    function check_directory($path)
    {
        $directory = dirname($path);

        $folders = explode('/', $directory);

        $dir = '';

        foreach( $folders as $folder)
        {
            $dir .= $folder . '/';

            if( ! File::isDirectory($dir) ) File::makeDirectory($dir);
        }

        return $path;
    }
}

if ( ! function_exists('globs') ) {

	function globs($locations)
	{
		// if it's just a single path
		if ( is_string($locations) )
			return glob($locations);

		// here we start with some awesomness
		$stack = [];

		foreach( $locations as $location )
		{
			$stack = array_merge($stack,glob($location));
		}

		return $stack;
	}
}


if ( ! function_exists('find_classes') ) {

	function find_classes($path, $root, $namespace = 'Bake')
	{
		$stack = [];

		$files = globs($path);

		foreach( $files as $file )
		{
			$class = preg_replace("|^".$root."|u", $namespace, $file);
			$class = str_replace('/', '\\', $class);
			$class = preg_replace("/.php$/u", "", $class);

			$stack[] = $class;
		}

		return $stack;
	}
}

