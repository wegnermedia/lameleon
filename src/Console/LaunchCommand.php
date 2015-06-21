<?php namespace Lameleon\Console;

use Illuminate\Console\Command;

class LaunchCommand extends Command
{
	/**
	 * The console command name.
	 *
	 * php artisan bake:resource System Languages Language
	 *
	 * @var string
	 */
	protected $name = 'lameleon:launch';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Simple Say Hello Command';

	/**
	 *
	 */
	public function fire()
	{
		$this->info(config('lameleon.foo','It works!'));
	}
} 