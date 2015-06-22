<?php namespace Melon\Support\Jobs\Contracts;

use Melon\Support\Jobs\Job;

interface BusService {

	public function dispatch(Job $job);

} 