<?php

use Melon\Jobs\Bus;
use Melon\Jobs\Contracts\BusService;
use Melon\Jobs\Job;

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
	 * @param \Melon\Jobs\Job $job
	 *
	 * @return mixed
	 */
	function dispatch(Job $job)
	{
		return app(BusService::class)->dispatch($job);
	}
}

