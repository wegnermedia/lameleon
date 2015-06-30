<?php namespace Melon\Console\Creator;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
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
	 * @var Collection
	 */
	protected $data;

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
		$component  = $this->argument('component');
		$name       = $this->argument('name');

		$this->collectData($component, $name);

		$this->createRequest();

		$this->info('Whohoooo, Request created ...');
	}
	
	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['component', InputArgument::REQUIRED, 'In which component do you want to create a resource (Base, Shop, Blog, Forum, Specials)'],
			['name', InputArgument::REQUIRED, 'Name of the resource (e.g. User, Post, Language or Order)'],
		];
	}
} 