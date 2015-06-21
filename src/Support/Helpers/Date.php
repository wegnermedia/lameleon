<?php

if ( ! function_exists('timestamp'))
{
	/**
	 * @return Carbon\Carbon
	 */
	function timestamp()
    {
	    return \Carbon\Carbon::now();
    }
}