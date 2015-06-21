<?php

if ( ! function_exists('get_namespace') ) {

	function get_namespace($class)
	{
		if ( is_object($class) )
			$class = get_class($class);

		return preg_replace("/\\\\\\w+$/u", "", $class);
	}
}

if ( ! function_exists('get_classname') ) {

	function get_classname($class)
	{
		return class_basename($class);
	}
}

