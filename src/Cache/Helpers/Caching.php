<?php

use Melon\Cache\Contracts\CacherService;

if ( ! function_exists('cacher') ) {

	/**
	 * @return CacherService
	 */
	function cacher()
	{
		return app(CacherService::class);
	}
}
