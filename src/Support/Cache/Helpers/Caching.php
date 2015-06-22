<?php

use Melon\Support\Cache\Contracts\CacherService;

if ( ! function_exists('cacher') ) {

	/**
	 * @return CacherService
	 */
	function cacher()
	{
		return app(CacherService::class);
	}
}
