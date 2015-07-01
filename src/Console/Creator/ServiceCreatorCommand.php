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
			['component', InputArgument::REQUIRED, 'Name of the component (e.g. Shop)'],
			['element', InputArgument::REQUIRED, 'Name of the component (e.g. Billing)'],
			['name', InputArgument::REQUIRED, 'Name of the service itself (e.g. PayPal)'],
		];
	}
} 