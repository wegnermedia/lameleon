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
	use CreatorsTrait;

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
	protected function collectData($component, $name)
	{
		$this->data = new Collection;

		// Model
		$this->data->put('model.name', $name);
		$this->data->put('model.table', $component . '__' . $name);
		$this->data->put('model.path', $this->repositoryPath($component, "Eloquent/{$name}.php"));
		$this->data->put('model.namespace', path_to_namespace($this->data->get('model.path')));
		$this->data->put('model.namespaced', $this->data->get('model.namespace') . '\\' . $name);

		// Model Contract
		$this->data->put('model_contract.name', $name);
		$this->data->put('model_contract.path', $this->repositoryPath($component, "{$name}.php"));
		$this->data->put('model_contract.namespace', path_to_namespace($this->data->get('model_contract.path')));
		$this->data->put('model_contract.namespaced', $this->data->get('model_contract.namespace') . '\\' . $name);

		// Model Translation
		$this->data->put('model_translation.name', $name . 'Translation');
		$this->data->put('model_translation.table', $this->data->get('model.table') . '_Translations');
		$this->data->put('model_translation.path', $this->repositoryPath($component, "Eloquent/{$name}Translation.php"));
		$this->data->put('model_translation.namespace', $this->data->get('model.namespace'));
		$this->data->put('model_translation.namespaced', $this->data->get('model.namespace') . '\\' . $name . 'Translation');

		// Presenter
		$this->data->put('presenter.name', $name . 'Presenter');
		$this->data->put('presenter.path', $this->presenterPath($component, $name . 'Presenter.php'));
		$this->data->put('presenter.namespace', path_to_namespace($this->data->get('presenter.path')));

		// Repository
		$this->data->put('repository.name', $name . 'Repository');
		$this->data->put('repository.path', $this->repositoryPath($component, "Eloquent/{$name}Repository.php"));
		$this->data->put('repository.namespace', path_to_namespace($this->data->get('repository.path')));
		$this->data->put('repository.namespaced', $this->data->get('repository.namespace') . '\\' . $name . 'Repository');

		// Repository Contract
		$this->data->put('repository_contract.name', $name . 'Repository');
		$this->data->put('repository_contract.path', $this->repositoryPath($component, "{$name}Repository.php"));
		$this->data->put('repository_contract.namespace', path_to_namespace($this->data->get('repository_contract.path')));
		$this->data->put('repository_contract.namespaced', $this->data->get('repository_contract.namespace') . '\\' . $name . 'Repository');

		// Service Provider
		$this->data->put('provider.name', $component . 'ServiceProvider');
		$this->data->put('provider.path', $this->providerPath("{$component}ServiceProvider.php"));
		$this->data->put('provider.namespace', path_to_namespace($this->data->get('provider.path')));
		$this->data->put('provider.namespaced', $this->data->get('provider.namespace') . '\\' . $component . 'ServiceProvider');

		// Migration
		$this->data->put('migration.name', "Create{$component}{$name}Tables" );
		$this->data->put('migration.filename', "create_{$component}_{$name}_tables" );
		$this->data->put('migration.path', base_path('database/migrations/' . date('Y_m_d_His') . '_' . $this->data->get('migration.filename'). '.php'));

		// Seeder
		$this->data->put('seeder.name', "{$component}{$name}TableSeeder");
		$this->data->put('seeder.path', $this->seederPath($this->data->get('seeder.name') . ".php"));

		// Event
		$this->data->put('event.name', $name);
		$this->data->put('event.path', $this->eventPath($component, $name . '.php'));
		$this->data->put('event.namespace', path_to_namespace($this->data->get('event.path')));

		// Job
		$this->data->put('job.name', $name);
		$this->data->put('job.path', $this->jobPath($component, $name . '.php'));
		$this->data->put('job.namespace', path_to_namespace($this->data->get('job.path')));

		// Request
		$this->data->put('request.name', $name . 'Request');
		$this->data->put('request.path', $this->requestPath($component, $name . 'Request.php'));
		$this->data->put('request.namespace', path_to_namespace($this->data->get('request.path')));

		// Service
		$this->data->put('service.name', $name);

		// Service Contract
		$this->data->put('service_contract.name', $name . 'Service');
		$this->data->put('service_contract.path', $this->servicePath($component, "{$name}/{$name}Service.php"));
		$this->data->put('service_contract.namespace', path_to_namespace($this->data->get('service_contract.path')));

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
		$binding = "\t\t" . '$this->app->bind(\\' . $this->data->get('repository_contract.namespaced') . '::class, \\' . $this->data->get('repository.namespaced') . '::class );' . "\n" ;

		$after_regex = "/register\\(\\)/uim";
		$before_regex = "/\\}$/uim";

		$this->insertContentIntoFile($this->data->get('provider.path'), $binding, $after_regex, $before_regex, $this->data->get('repository_contract.namespaced'), true);
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

} 