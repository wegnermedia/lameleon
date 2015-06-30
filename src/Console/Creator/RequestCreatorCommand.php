<?php namespace Melon\Console\Creator;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class ResourceCreatorCommand
 *
 * @package Melon\Console\Creators
 */
class RequestCreatorCommand extends Command
{
	use MelonCreatorTrait;

	/**
	 * The console command name.
	 *
	 * php artisan create:resource
	 *
	 * @var string
	 */
	protected $name = 'create:request';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'The Melon Way to create a new request ...';

	/**
	 *
	 */
	public function handle()
	{
		$name = $this->argument('name');

		$this->createRequest($name);

		$this->info('Whohoooo, Request created ...');
	}


	/**
	 * Create a new Request
	 *
	 * @param $component
	 *
	 * @return $this
	 */
	protected function createRequest($name)
	{
		$path = app_path("Http/Requests/{$name}.php");

		return $this->create('Request', $path, compact('name'));
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['name', InputArgument::REQUIRED, 'Name of the request (e.g. RegisteringCustomer)'],
		];
	}
} 