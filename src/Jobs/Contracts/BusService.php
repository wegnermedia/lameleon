<?php namespace Melon\Jobs\Contracts;

use Melon\Jobs\Job;

interface BusService {

	public function dispatch(Job $job);

} 