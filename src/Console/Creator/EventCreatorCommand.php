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
class EventCreatorCommand extends Command
{
	use MelonCreatorTrait;

	/**
	 * The console command name.
	 *
	 * php artisan create:resource
	 *
	 * @var string
	 */
	protected $name = 'create:event';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'The Melon Way to create a new event ...';

	/**
	 *
	 */
	public function handle()
	{
		$name = $this->argument('name');

		$this->createEvent($name);

		$this->info('Whohoooo, Event created ...');
	}


	/**
	 * Create a new service contract
	 *
	 * @param $component
	 *
	 * @return $this
	 */
	protected function createEvent($name)
	{
		$path = app_path("Events/{$name}.php");

		return $this->create('Event', $path, compact('name'));
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