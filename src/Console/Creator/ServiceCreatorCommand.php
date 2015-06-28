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
		$component = $this->argument('component');
		$name = $this->argument('name');

		$this->createServiceContract($component);
		$this->createService($component, $name);
		$this->addToAppServiceProvider($component, $name);

		$this->info('Whohoooo, Service created ...');
	}


	/**
	 * Create a new service contract
	 *
	 * @param $component
	 *
	 * @return $this
	 */
	protected function createServiceContract($component)
	{
		$name = $component . 'Service';

		$path = app_path("Services/{$component}/{$name}.php");

		return $this->create('ServiceContract', $path, compact('component', 'name'));
	}


	/**
	 * Create a new Service Class for a component
	 *
	 * @param $component
	 * @param $name
	 *
	 * @return $this
	 */
	protected function createService($component, $name)
	{
		$name = $name . $component . 'Service';

		$path = app_path("Services/{$component}/{$name}.php");

		return $this->create('Service', $path, compact('component', 'name'));
	}


	/**
	 * Add the new Service to the ServiceProvider
	 *
	 * @param $component
	 * @param $name
	 *
	 * @return $this
	 */
	protected function addToAppServiceProvider($component, $name)
	{
		$path = app_path('Providers/AppServiceProvider.php');

		if ( ! file_exists($path) )
		{
			$name = 'AppServiceProvider';

			// First, create the Provider
			$this->create('ServiceProvider', $path, compact('name'));

			// Now Add provider to the app config file ...
			$content = "\t\t" . config('melon.app_name', 'App') . '\Providers\AppServiceProvider::class,' . "\n";

			$this->insertContentIntoFile(config_path('app.php'), $content, "'providers'", "],", 'AppServiceProvider');
		}

		$contract = config('melon.app_name', 'App') . '\Services\\' . $component . '\\' . $component . 'Service';
		$service  = config('melon.app_name', 'App') . '\Services\\' . $component . '\\' . $name . $component . 'Service';

		$singleton = $this->getTemplate('ServiceProviderSingleton', compact('contract', 'service'));

		$after_regex = "/register\\(\\)/uim";
		$before_regex = "/\\}$/uim";

		return $this->insertContentIntoFile($path , $singleton, $after_regex, $before_regex, $contract, true);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['component', InputArgument::REQUIRED, 'Name of the component (e.g. Billing)'],
			['name', InputArgument::REQUIRED, 'Name of the service itself (e.g. PayPal)'],
		];
	}
} 