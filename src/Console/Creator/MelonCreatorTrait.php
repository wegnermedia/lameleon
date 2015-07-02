<?php namespace Melon\Console\Creator;

use File;
use Illuminate\Support\Collection;
use Melon\Console\Creator\Traits\CreatorsTrait;
use Melon\Console\Creator\Traits\PathsTrait;

/**
 * Class MelonCreatorTrait
 *
 * @package Melon\Console\Creator
 */
trait MelonCreatorTrait
{
	use PathsTrait;

	/**
	 * @param       $template
	 * @param array $arguments
	 *
	 * @return mixed|null|string
	 */
	protected function getTemplate($template, array $arguments = [])
	{
		// Get the Template and replace params
		$template = __DIR__ . '/Stubs/' . $template . '.stub';

		if( ! file_exists($template)) return null;

		$raw = File::get($template);

		foreach($arguments as $search => $replace)
		{
			$raw = str_replace('{' . $search . '}', $replace, $raw);
		}

		return $raw;
	}

	/**
	 * @param       $template
	 * @param       $path
	 * @param array $arguments
	 *
	 * @return $this
	 */
	protected function create($template, $path, array $arguments = [])
	{
		if(file_exists($path)) return $this;

		check_directory($path);

		$content = $this->getTemplate($template, $arguments);

		File::put($path, $content);

		return $this;
	}



	/**
	 * Get the applications root name.
	 *
	 * @return mixed
	 */
	protected function getRoot()
	{
		return config('melon.app_name', 'App');
	}

	/**
	 * @param array $lines
	 * @param       $content
	 *
	 * @return int
	 */
	protected function contentAlreadyExists($haystack, $pattern, $regex = false)
	{
		if ( is_array($haystack) )
			$haystack = implode(' ', $haystack);

		$pattern = $regex ? $pattern : "#".preg_quote($pattern)."#u";

		return preg_match($pattern, $haystack);
	}

	/**
	 * @param $file
	 * @param $content
	 * @param $indicator
	 * @param $pattern
	 *
	 * @return $this
	 */
	public function appendContentToFile($file, $content, $indicator, $pattern, $regex = false )
	{
		$lines = file($file);

		if($this->contentAlreadyExists($lines, $indicator))
			return $this;

		array_insert_at_pattern($lines, [ $pattern => $content], $regex);

		File::delete($file);
		File::put($file, implode("", $lines));

		return $this;
	}

	/**
	 * Insert content into a file between a pattern
	 *
	 * @param      $path
	 * @param      $content
	 * @param      $after
	 * @param      $before
	 * @param null $indicator
	 *
	 * @return $this
	 */
	protected function insertContentIntoFile($path, $content, $after, $before, $indicator = null, $regex = false)
	{
		$lines = file($path);

		$indicator = is_null($indicator) ? $content : $indicator;

		if( $this->contentAlreadyExists($lines, $indicator) )
			return $this;

		array_insert_into($lines, $content, $after, $before, $regex);

		File::delete($path);
		File::put($path, implode("", $lines));

		return $this;
	}


	/**
	 * Collect all data for the resource creation process
	 *
	 * @param $component
	 * @param $name
	 *
	 * @return $this
	 */
	protected function collectData($component, $name, $element = null)
	{
		$this->data = new Collection;

		// Model + Model Contract + Translation Model
		$this->collectModelData($component, $name);

		// Presenter
		$this->collectPresenterData($component, $name);

		// Repository
		$this->collectRepositoryData($component, $name);

		// Service Provider
		$this->collectServiceProviderData($component);

		// Migration
		$this->collectMigrationData($component, $name);

		// Seeder
		$this->collectSeederData($component, $name);

		// Event
		$this->collectEventData($component, $name);

		// Job
		$this->collectJobData($component, $name);

		// Request
		$this->collectRequestData($element, $component, $name );

		// Service
		$this->collectServiceData( $element , $component, $name);

		// Controller
		$this->collectControllerData($element, $component, $name);

		return $this;
	}

	/**
	 * Create a new Model Class
	 */
	protected function createModel()
	{
		$data = [
			'name'       => $this->data->get('model.name'),
			'table'      => $this->data->get('model.table'),
			'namespace'  => $this->data->get('model.namespace'),
			'contract'   => $this->data->get('model_contract.name'),
			'contract_namespaced'  => $this->data->get('model_contract.namespaced'),
		];

		$this->create('Model', $this->data->get('model.path'), $data);
	}

	/**
	 * Create a new Model Translation Class
	 */
	protected function createModelTranslation()
	{
		$data = [
			'name'       => $this->data->get('model_translation.name'),
			'table'      => $this->data->get('model_translation.table'),
			'namespace'  => $this->data->get('model_translation.namespace'),
		];

		$this->create('ModelTranslation', $this->data->get('model_translation.path'), $data);
	}

	/**
	 * Create a new Model Class
	 */
	protected function createModelContract()
	{
		$data = [
			'name'       => $this->data->get('model.name'),
			'namespace'  => $this->data->get('model_contract.namespace'),
		];

		$this->create('ModelContract', $this->data->get('model_contract.path'), $data);
	}

	/**
	 * Create a new Repository Class
	 */
	protected function createRepository()
	{
		$data = [
			'name'       => $this->data->get('repository.name'),
			'namespace'  => $this->data->get('repository.namespace'),
			'contract'   => $this->data->get('repository_contract.name'),
			'contract_namespaced'  => $this->data->get('repository_contract.namespaced'),
			'model'  => $this->data->get('model.name'),
		];

		$this->create('Repository', $this->data->get('repository.path'), $data);
	}

	/**
	 * Create a new Repository Contract Class
	 */
	protected function createRepositoryContract()
	{
		$data = [
			'name'       => $this->data->get('repository_contract.name'),
			'namespace'  => $this->data->get('repository_contract.namespace'),
		];

		$this->create('RepositoryContract', $this->data->get('repository_contract.path'), $data);
	}

	/**
	 * Create a new Presenter Class
	 */
	protected function createPresenter()
	{
		$data = [
			'name'       => $this->data->get('presenter.name'),
			'namespace'  => $this->data->get('presenter.namespace'),
		];

		$this->create('Presenter', $this->data->get('presenter.path'), $data);
	}

	/**
	 * Create a new
	 *
	 * @param        $name
	 * @param        $table
	 * @param string $template
	 *
	 * @return $this
	 */
	protected function createMigration($table, $template = 'Default')
	{
		$this->createMigrationIfNotExists();

		// Get the File Contents and fill it with
		$lines = file($this->data->get('migration.path'));

		// Check for the Schema, if the schema for this table already exists
		if ( $this->contentAlreadyExists($lines, "'{$table}'"))
			return $this;

		// Get the Migration Schema Template and append it
		$template = 'MigrationSchema' . $template;

		array_insert_at_pattern($lines, [ '// up()' => $this->getTemplate($template, [ 'table' => $table]) ]);
		array_insert_at_pattern($lines, [ '// down()' => "\t\t" . 'Schema::dropIfExists("'.$table.'");'."\n" ]);

		File::delete($this->data->get('migration.path'));
		File::put($this->data->get('migration.path'), implode("", $lines));

		return $this;
	}


	/**
	 * Create a migration file if it not exists.
	 *
	 * @return $this
	 */
	protected function createMigrationIfNotExists()
	{
		// Check if Migration already exists
		$file = glob(base_path('database/migrations/*' . $this->data->get('migration.filename') . '.php'));

		if ( ! empty($file) )
		{
			// Jap, migration exists ... Just update the path and we're done
			$this->data->put('migration.path', $file[0]);

			return $this;
		}

		// No Matches? Create a new Migration!
		$this->create('Migration', $this->data->get('migration.path'), [ 'name' => $this->data->get('migration.name') ]);

		return $this;
	}


	/**
	 * Create a new Table Seeder for a Model
	 *
	 * @param $name
	 * @param $namespace_root
	 *
	 * @return $this
	 */
	protected function createTableSeeder()
	{
		$data = [
			'name'   => $this->data->get('seeder.name'),
			'model'  => $this->data->get('model.name'),
			'model_namespaced'  => $this->data->get('model.namespaced'),
		];

		$this->create('Seeder', $this->data->get('seeder.path'), $data);

		$this->appendTableSeeder();

		return $this;
	}


	/**
	 * Append the Seeder to the database Seeder
	 *
	 * @return $this
	 */
	protected function appendTableSeeder()
	{
		$path = base_path('database/seeds/DatabaseSeeder.php');

		$content = "\t\t" . '// $this->call(' . $this->data->get('seeder.name') . '::class);' . "\n";
		$after  = 'unguard()';
		$before = 'reguard()';

		return $this->insertContentIntoFile($path , $content, $after, $before, $this->data->get('seeder.name'));
	}

	/**
	 * Add the repository binding to the service provider
	 */
	protected function addRepositoryBindingToServiceProvider()
	{
		$this->createServiceProviderIfNotExists();

		// Okay, now the binding
		$binding = "\n\t\t" . '$this->app->bind(\\' . $this->data->get('repository_contract.namespaced') . '::class, \\' . $this->data->get('repository.namespaced') . '::class );' . "\n" ;

		$data = [
			'name'  => $this->data->get('model.name'),
			'contract_namespaced'  => $this->data->get('repository_contract.namespaced'),
			'repository_namespaced'  => $this->data->get('repository.namespaced'),
		];

		$content = $this->getTemplate('ServiceProviderBinding', $data);

		$after_regex = "/register\\(\\)/uim";
		$before_regex = "/\\}$/uim";

		$this->insertContentIntoFile($this->data->get('provider.path'), $content, $after_regex, $before_regex, $this->data->get('repository_contract.namespaced'), true);
	}

	/**
	 * Add the repository binding to the service provider
	 */
	protected function addSingletonToServiceProvider()
	{
		$this->createServiceProviderIfNotExists();

		// Okay, now the singleton
		$data = [
			'contract'  => $this->data->get('service_contract.name'),
			'service_namespaced'  => $this->data->get('service.namespaced'),
			'contract_namespaced' => $this->data->get('service_contract.namespaced'),
		];

		$content = $this->getTemplate('ServiceProviderSingleton', $data);

		$after_regex = "/register\\(\\)/uim";
		$before_regex = "/\\}$/uim";

		$this->insertContentIntoFile($this->data->get('provider.path'), $content, $after_regex, $before_regex, $this->data->get('service_contract.namespaced'), true);
	}

	/**
	 * @param $provider_path
	 */
	protected function createServiceProviderIfNotExists()
	{
		if ( ! file_exists($this->data->get('provider.path') ) )
		{
			$data = [
				'namespace' => $this->data->get('provider.namespace'),
				'name' => $this->data->get('provider.name'),
			];

			$this->create('ServiceProvider', $this->data->get('provider.path'), $data);

			// Now Add provider to the melon config file ...
			$content = "\t\t" . $this->data->get('provider.namespaced') . "::class," . "\n";

			$this->insertContentIntoFile(config_path('melon.php'), $content, "'providers'", "],", $this->data->get('provider.name'));
		}
	}


	/**
	 * Create a new Event Class
	 */
	protected function createEvent()
	{
		$data = [
			'name' => $this->data->get('event.name'),
			'namespace' => $this->data->get('event.namespace'),
		];

		$this->create('Event', $this->data->get('event.path'), $data);
	}


	/**
	 * Create a new job class.
	 */
	protected function createJob()
	{
		$data = [
			'name' => $this->data->get('job.name'),
			'namespace' => $this->data->get('job.namespace'),
		];

		$this->create('Job', $this->data->get('job.path'), $data);
	}

	/**
	 * Create a new Request class.
	 */
	protected function createRequest()
	{
		$data = [
			'name' => $this->data->get('request.name'),
			'namespace' => $this->data->get('request.namespace'),
		    'root'  => config('melon.app_name', 'App'),
		];

		$this->create('Request', $this->data->get('request.path'), $data);
	}

	/**
	 * Create a new Service class.
	 */
	protected function createService()
	{
		$data = [
			'name' => $this->data->get('service.name'),
			'namespace' => $this->data->get('service.namespace'),
			'contract' => $this->data->get('service_contract.name'),
			'contract_namespaced' => $this->data->get('service_contract.namespaced'),
		];

		$this->create('Service', $this->data->get('service.path'), $data);
	}

	/**
	 * Create a new Service class.
	 */
	protected function createServiceContract()
	{
		$data = [
			'name' => $this->data->get('service_contract.name'),
			'namespace' => $this->data->get('service_contract.namespace'),
		];

		$this->create('ServiceContract', $this->data->get('service_contract.path'), $data);
	}

	/**
	 * Create a new Controller class.
	 */
	protected function createController()
	{
		$this->createParentControllerIfNotExists();

		$this->create('Controller', $this->data->get('controller.path'), [
			'name'      => $this->data->get('controller.name'),
			'namespace' => $this->data->get('controller.namespace'),
			'parent'    => $this->data->get('base_controller.name'),
			'parent_namespaced'    => $this->data->get('base_controller.namespaced'),
		]);
	}

	protected function createParentControllerIfNotExists()
	{
		// Root Controller
		$this->create('ParentController', $this->data->get('root_controller.path'), [
			'name'      => $this->data->get('root_controller.name'),
			'namespace' => $this->data->get('root_controller.namespace'),
		    'parent'    => 'Controller',
		    'parent_namespaced' => config('melon.app_name', 'App') . '\Http\Controllers\Controller',
		]);

		// Base Controller
		$this->create('ParentController', $this->data->get('base_controller.path'), [
			'name'      => $this->data->get('base_controller.name'),
			'namespace' => $this->data->get('base_controller.namespace'),
			'parent'    => $this->data->get('root_controller.name'),
			'parent_namespaced' => $this->data->get('root_controller.namespaced'),
		]);
	}

	/**
	 * @param $component
	 * @param $name
	 */
	protected function collectModelData($component, $name)
	{
		$this->data->put('model.name', $name);
		$this->data->put('model.table', $component . '__' . $name);
		$this->data->put('model.path', $this->repositoryPath($component, "Eloquent/{$this->data->get('model.name')}.php"));
		$this->data->put('model.namespace', path_to_namespace($this->data->get('model.path')));
		$this->data->put('model.namespaced', $this->data->get('model.namespace') . '\\' . $this->data->get('model.name'));

		$this->data->put('model_contract.name', $name);
		$this->data->put('model_contract.path', $this->repositoryPath($component, "{$this->data->get('model_contract.name')}.php"));
		$this->data->put('model_contract.namespace', path_to_namespace($this->data->get('model_contract.path')));
		$this->data->put('model_contract.namespaced', $this->data->get('model_contract.namespace') . '\\' . $this->data->get('model_contract.name') );

		$this->data->put('model_translation.name', $name . 'Translation');
		$this->data->put('model_translation.table', $this->data->get('model.table') . '_Translations');
		$this->data->put('model_translation.path', $this->repositoryPath($component, "Eloquent/{$this->data->get('model_translation.name')}.php"));
		$this->data->put('model_translation.namespace', $this->data->get('model.namespace'));
		$this->data->put('model_translation.namespaced', $this->data->get('model.namespace') . '\\' . $this->data->get('model_translation.name'));
	}


	/**
	 * @param $component
	 * @param $name
	 */
	protected function collectPresenterData($component, $name)
	{
		$this->data->put('presenter.name', preg_replace("/(Presenter)+$/uism", "Presenter", $name) . 'Presenter');
		$this->data->put('presenter.path', $this->presenterPath($component, $this->data->get('presenter.name') . '.php'));
		$this->data->put('presenter.namespace', path_to_namespace($this->data->get('presenter.path')));
		$this->data->put('presenter.namespaced', $this->data->get('presenter.namespace') . '\\' . $this->data->get('presenter.name'));
	}


	/**
	 * @param $component
	 * @param $name
	 */
	protected function collectRepositoryData($component, $name)
	{
		$this->data->put('repository.name', preg_replace("/(Repository)+$/uism", "Repository", $name) . 'Repository');
		$this->data->put('repository.path', $this->repositoryPath($component, "Eloquent/{$this->data->get('repository.name')}.php"));
		$this->data->put('repository.namespace', path_to_namespace($this->data->get('repository.path')));
		$this->data->put('repository.namespaced', $this->data->get('repository.namespace') . '\\' . $this->data->get('repository.name'));

		$this->data->put('repository_contract.name', preg_replace("/(Repository)+$/uism", "Repository", $name) . 'Repository');
		$this->data->put('repository_contract.path', $this->repositoryPath($component, $this->data->get('repository_contract.name') . ".php"));
		$this->data->put('repository_contract.namespace', path_to_namespace($this->data->get('repository_contract.path')));
		$this->data->put('repository_contract.namespaced', $this->data->get('repository_contract.namespace') . '\\' . $this->data->get('repository_contract.name'));
	}


	/**
	 * @param $component
	 */
	protected function collectServiceProviderData($component)
	{
		$this->data->put('provider.name', preg_replace("/(ServiceProvider)+$/uism", "ServiceProvider", $component) . 'ServiceProvider');
		$this->data->put('provider.path', $this->providerPath($this->data->get('provider.name') . ".php"));
		$this->data->put('provider.namespace', path_to_namespace($this->data->get('provider.path')));
		$this->data->put('provider.namespaced', $this->data->get('provider.namespace') . '\\' . $this->data->get('provider.name'));
	}


	/**
	 * @param $component
	 * @param $name
	 */
	protected function collectMigrationData($component, $name)
	{
		$this->data->put('migration.name', "Create{$component}{$name}Tables");
		$this->data->put('migration.filename', "create_{$component}_{$name}_tables");
		$this->data->put('migration.path', base_path('database/migrations/' . date('Y_m_d_His') . '_' . $this->data->get('migration.filename') . '.php'));
	}


	/**
	 * @param $component
	 * @param $name
	 */
	protected function collectSeederData($component, $name)
	{
		$this->data->put('seeder.name', "{$component}{$name}TableSeeder");
		$this->data->put('seeder.path', $this->seederPath($this->data->get('seeder.name') . ".php"));
	}


	/**
	 * @param $component
	 * @param $name
	 */
	protected function collectEventData($component, $name)
	{
		$this->data->put('event.name', preg_replace("/(Event)+$/uism", "Event", $name) . 'Event');
		$this->data->put('event.path', $this->eventPath($component, $this->data->get('event.name') . '.php'));
		$this->data->put('event.namespace', path_to_namespace($this->data->get('event.path')));
	}


	/**
	 * @param $component
	 * @param $name
	 */
	protected function collectJobData($component, $name)
	{
		$this->data->put('job.name', $name);
		$this->data->put('job.path', $this->jobPath($component, $name . '.php'));
		$this->data->put('job.namespace', path_to_namespace($this->data->get('job.path')));
	}


	/**
	 * @param $component
	 * @param $name
	 * @param $element
	 */
	protected function collectRequestData($element, $component, $name)
	{
		$this->data->put('request.name', preg_replace("/(Request)+$/uism", "", $name) . 'Request');
		$this->data->put('request.path', $this->requestPath($component, $element, $this->data->get('request.name') . '.php'));
		$this->data->put('request.namespace', path_to_namespace($this->data->get('request.path')));
	}


	/**
	 * @param $component
	 * @param $name
	 * @param $element
	 */
	protected function collectServiceData($element, $component, $name)
	{
		$this->data->put('service.name', $name);
		$this->data->put('service.path', $this->servicePath($component, $element, $name . '.php'));
		$this->data->put('service.namespace', path_to_namespace($this->data->get('service.path')));
		$this->data->put('service.namespaced', $this->data->get('service.namespace') . '\\' . $name);

		// Service Contract
		$this->data->put('service_contract.name', preg_replace("/(Service)+$/uism", "Service", $element) . 'Service');
		$this->data->put('service_contract.path', $this->servicePath($component, $element, $this->data->get('service_contract.name') . ".php"));
		$this->data->put('service_contract.namespace', path_to_namespace($this->data->get('service_contract.path')));
		$this->data->put('service_contract.namespaced', $this->data->get('service_contract.namespace') . '\\' . $this->data->get('service_contract.name'));
	}


	/**
	 * @param $name
	 */
	protected function collectControllerData($element, $component, $name)
	{
		$this->data->put('controller.name', preg_replace("/(Controller)+$/uism", "Controller", $name) . 'Controller');
		$this->data->put('controller.path', $this->controllerPath($element, $component, $this->data->get('controller.name') . ".php"));
		$this->data->put('controller.namespace', path_to_namespace($this->data->get('controller.path')));
		$this->data->put('controller.namespaced', $this->data->get('controller.namespace') . '\\' . $this->data->get('controller.name'));

		// Base Controller for this controller
		// App\Http\Controllers\Frontend\Shop\FrontendShopController
		$this->data->put('base_controller.name', "{$element}{$component}Controller");
		$this->data->put('base_controller.path', $this->controllerRootPath($element . '/' . $component . '/' . $this->data->get('base_controller.name') . ".php"));
		$this->data->put('base_controller.namespace', path_to_namespace($this->data->get('base_controller.path')));
		$this->data->put('base_controller.namespaced', $this->data->get('base_controller.namespace') . '\\' . $this->data->get('base_controller.name'));

		// The Element root controller
		// App\Http\Controllers\Frontend\FrontendController
		$this->data->put('root_controller.name', "{$element}Controller");
		$this->data->put('root_controller.path', $this->controllerRootPath($element . '/' . $this->data->get('root_controller.name') . ".php"));
		$this->data->put('root_controller.namespace', path_to_namespace($this->data->get('root_controller.path')));
		$this->data->put('root_controller.namespaced', $this->data->get('root_controller.namespace') . '\\' . $this->data->get('root_controller.name'));
	}

}