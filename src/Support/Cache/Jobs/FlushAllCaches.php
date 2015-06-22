<?php namespace Melon\Support\Cache\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Melon\Support\Jobs\Job;
use Artisan;

class FlushAllCaches extends Job implements SelfHandling {

	public function handle()
	{
		Artisan::call('cache:clear');

		vd('Cache flushed');
	}
}