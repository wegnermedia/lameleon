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
class ControllerCreatorCommand extends Command
{
	use MelonCreatorTrait;

	/**
	 * The console command name.
	 *
	 * php artisan create:controller
	 *
	 * @var string
	 */
	protected $name = 'create:controller';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'The Melon Way to create a new Controller ...';

	/**
	 *
	 */
	public function handle()
	{

		// art create:controller Backend Base LanguageController
		$component = $this->argument('component');
		$element = $this->argument('element');
		$name = $this->argument('name');

		$this->collectData($component, $name, $element);

		$this->createController();

		$this->info('Whohoooo, Controller created ...');
	}


	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['element', InputArgument::REQUIRED, 'Name of the element (Frontend | Backend | Api)'],
			['component', InputArgument::REQUIRED, 'Name of the component (e.g. Shop, Blog, Base, Forum)'],
			['name', InputArgument::REQUIRED, 'Name of the service itself (e.g. LanguageController)'],
		];
	}
} 