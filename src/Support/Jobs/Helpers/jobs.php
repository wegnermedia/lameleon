<?php

use Melon\Support\Jobs\Bus;
use Melon\Support\Jobs\Contracts\BusService;
use Melon\Support\Jobs\Job;

if ( ! function_exists('bus') ) {

	/**
	 * @return Bus
	 */
	function bus()
	{
		return app(BusService::class);
	}
}

if ( ! function_exists('dispatch') ) {

	/**
	 * @param \Melon\Support\Jobs\Job $job
	 *
	 * @return mixed
	 */
	function dispatch(Job $job)
	{
		return app(BusService::class)->dispatch($job);
	}
}

