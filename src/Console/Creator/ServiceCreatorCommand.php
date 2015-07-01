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
class ServiceCreatorCommand extends Command
{
	use MelonCreatorTrait;

	/**
	 * The console command name.
	 *
	 * php artisan create:resource
	 *
	 * @var string
	 */
	protected $name = 'create:service';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'The Melon Way to create a new service with contract, service, ServiceProvider ...';

	/**
	 *
	 */
	public function handle()
	{

		// art create:service Shop Billing PayPal
		$component = $this->argument('component');
		$element = $this->argument('element');
		$name = $this->argument('name');

		$this->collectData($component, $name, $element);

		$this->createService();
		$this->createServiceContract();

		$this->addSingletonToServiceProvider();

		$this->info('Whohoooo, Service created ...');
	}


	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['component', InputArgument::REQUIRED, 'Name of the component (e.g. Shop)'],
			['element', InputArgument::REQUIRED, 'Name of the component (e.g. Billing)'],
			['name', InputArgument::REQUIRED, 'Name of the service itself (e.g. PayPal)'],
		];
	}
} 