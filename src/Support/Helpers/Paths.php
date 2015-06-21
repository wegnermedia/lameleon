<?php

if ( ! function_exists('sites_path') ) {

	function sites_path($path = '')
	{
		return base_path('resources/sites/') . trim($path, '/');
	}
}
