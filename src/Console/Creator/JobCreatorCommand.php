<?php namespace Melon\Console\Creator;

use File;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class ResourceCreatorCommand
 *
 * @package Melon\Console\Creators
 */
class JobCreatorCommand extends Command
{
	use MelonCreatorTrait;

	/**
	 * The console command name.
	 *
	 * php artisan create:resource
	 *
	 * @var string
	 */
	protected $name = 'create:job';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'The Melon Way to create a new job ...';

	/**
	 *
	 */
	public function handle()
	{
		$name = $this->argument('name');

		$this->createJob($name);

		$this->info('Whohoooo, Job created ...');
	}


	/**
	 * Create a new service contract
	 *
	 * @param $component
	 *
	 * @return $this
	 */
	protected function createJob($name)
	{
		$path = app_path("Jobs/{$name}.php");

		return $this->create('Job', $path, compact('name'));
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['name', InputArgument::REQUIRED, 'Name of the job (e.g. FlushAllCaches)'],
		];
	}
} 