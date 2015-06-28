<?php

use Illuminate\Support\Collection;

if ( ! function_exists('export_array_to_string') ) {

	/**
	 * @param array $array
	 * @param int   $lvl
	 *
	 * @return string
	 */
	function export_array_to_string(array $array, $lvl = 0)
	{
		$sub = $lvl + 1;

		$return = "";

		if($lvl == null)
			$return = "[\n";

		foreach($array as $key => $mixed)
		{
			$key = trim($key);

			if ( $mixed instanceof Collection )
				$mixed = $mixed->toArray();

			if( is_string($mixed))
				$mixed = trim($mixed);

			if(empty($key) && empty($mixed))
				continue;

			if( ! is_numeric($key) && ! empty($key))
				$key = $key == "[]" ? null : '"' . addslashes($key) . '"';


			if(is_array($mixed))
			{
				if($key !== null) {
					$return .= "\t" . str_repeat("\t", $sub) . "$key => [\n";
					$return .= export_array_to_string($mixed, $sub);
					$return .= "\t" . str_repeat("\t", $sub) . "],\n";
				} else {
					$return .= "\t" . str_repeat("\t", $sub) . "[\n";
					$return .= export_array_to_string($mixed, $sub);
					$return .= "\t" . str_repeat("\t", $sub) . "],\n";
				}
			}
			else {

				if($key !== null) {
					$return .= "\t" . str_repeat("\t", $sub) . $key . ' => ';
				} else {
					$return .= "\t" . str_repeat("\t", $sub);
				}

				// Add the Value ...
				if ( is_string($mixed) ) {
					$return .= '"'.addslashes($mixed).'"'. ",\n";
				} else if ( is_null($mixed)) {
					$return .= 'null'. ",\n";
				} else if ( $mixed === true ) {
					$return .= 'true'. ",\n";
				} else if ( $mixed === false ) {
					$return .= 'false'. ",\n";
				}

			}
		}
		if($lvl == null)
			$return .= "\t];\n";

		return $return;
	}
}

if ( ! function_exists('array_keys_exist'))
{
	/**
	 * @param array $keys
	 * @param array $array
	 *
	 * @return bool
	 */
	function array_keys_exist(array $keys, array $array)
	{
		foreach( $keys as $key)
		{
			if ( ! array_key_exists($key, $array))
				return false;
		}
		return true;
	}
}

if ( ! function_exists('one_in_array') ) {

	/**
	 * @param array $needles
	 * @param array $haystack
	 *
	 * @return bool
	 */
	function one_in_array(array $needles, array $haystack)
	{
		foreach($needles as $needle)
		{
			if (in_array($needle, $haystack))
				return true;
		}

		return false;
	}
}

if ( ! function_exists('array_insert_at_pattern') ) {

	/**
	 * Take an Array and add a given Pattern some content
	 *
	 * @param $array
	 * @param $inserts
	 *
	 * @return array
	 */
	function array_insert_at_pattern(array &$array, array $inserts, $regex = false)
	{
		foreach ( $inserts as $pattern => $content)
		{
			$count = count($array);

			$pattern = $regex ? $pattern : "|".preg_quote($pattern)."|u";

			for( $i = 0; $i < $count; $i++ )
			{
				// Search for pattern or skip
				if ( ! preg_match($pattern, $array[$i]) ) continue;

				// we have a winner, add the content
				array_splice($array, $i, 0, $content);

				// raise the iteration count to avoid duplicate entries
				$insert_count = is_array($content) ? count($content) : 1;
				$i += $insert_count;
			}
		}

		return $array;
	}
}

if ( ! function_exists('array_insert_at_position') ) {

	function array_insert_at_position(array &$array, $content, $position)
	{
		array_splice($array, $position, 0, $content);
	}
}

if ( ! function_exists('array_insert_into') ) {

    function array_insert_into(array &$array, $content, $after, $before, $regex = false)
    {
        $is_in_between = false;

        $count = count($array);

	    // Regex or a simple string pattern?
	    $after_pattern  = $regex ? $after  : "|" . preg_quote($after) . "|u";
	    $before_pattern = $regex ? $before : "|" . preg_quote($before) . "|u";

	    for( $i = 0; $i < $count; $i++ )
	    {
		    if ( $is_in_between === true )
		    {
		        // Great we are in the right context
			    if ( preg_match($before_pattern, $array[$i]) )
		        {
			        // we have a winner, add the content
				    array_splice($array, $i - 1, 0, $content);

                    return $array;
		        }
		    }

	        if ( preg_match($after_pattern, $array[$i]) )
		        $is_in_between = true;
	    }

	    return $array;
    }

}
