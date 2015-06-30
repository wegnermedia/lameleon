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
class ResourceCreatorCommand extends Command
{
	use MelonCreatorTrait;

	/**
	 * The console command name.
	 *
	 * php artisan create:resource
	 *
	 * @var string
	 */
	protected $name = 'create:resource';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'The Melon Way to create a new resource with model, repository, presenter, migration, seeder ...';

	/**
	 *
	 */
	public function handle()
	{
		$component  = $this->argument('component');
		$name       = $this->argument('name');
		$table      = $this->toTable($component, $name, $this->option('table'));

		$translatable = $this->confirm('Is it translatable?');

		$this->createModel($component, $name, $table);
		$this->createRepository($component, $name);
		$this->createPresenter($component, $name);
		$this->createMigration($component, $name, $table, 'Default');
		$this->createTableSeeder($component, $name);

		if ( $translatable )
		{
			$this->createModel($component, $name . 'Translation', $this->toTranslationTable($table));
			$this->createMigration($component, $name, $this->toTranslationTable($table), 'Translation');
		}

		$this->info('Whohoooo, Resource created ...');
	}

	/**
	 * Create a new
	 *
	 * @param $name
	 * @param $table
	 *
	 * @return \Melon\Console\Creator\ResourceCreatorCommand
	 */
	protected function createModel($component, $name, $table)
	{
		$path       = $this->repositoryPath($component, $name . '.php');
		$namespace  = path_to_namespace($path);

		return $this->create('Model', $path, compact('component', 'name', 'table', 'namespace'));
	}



	/**
	 * Create a new repository class
	 *
	 * @param $name
	 *
	 * @return $this
	 */
	protected function createRepository($component, $name)
	{
		$model = $name;
		$name = $name . 'Repository';
		$path = $this->repositoryPath($component, $name . '.php');
		$namespace = path_to_namespace($path);

		return $this->create('Repository', $path, compact('component', 'name', 'model', 'namespace'));
	}

	/**
	 * Create a new presenter class
	 *
	 * @param $name
	 *
	 * @return $this
	 */
	protected function createPresenter($component, $name)
	{
		$name = $name . 'Presenter';
		$path = $this->presenterPath($component, $name . '.php');
		$namespace = path_to_namespace($path);

		return $this->create('Presenter', $path, compact('name', 'namespace'));
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
	protected function createMigration($component, $name, $table, $template = 'Default')
	{
		$filename = "create_{$component}_{$name}_tables";

		// Check if Migration already exists
		$file = glob(base_path('database/migrations/*'.$filename.'.php'));

		if ( empty($file) )
		{
			// No Matches? Create a new Migration!
			$path = base_path('database/migrations/' . date('Y_m_d_His') . '_' . $filename . '.php');

			// create_SomeStuff_tables --> CreateSomeStuffTables
			$this->create('Migration', $path, [ 'name' => to_case($filename) ]);
			$file[] = $path;
		}

		$path = $file[0];

		// Get the File Contents and fill it with
		$lines = file($path);

		// Check for the Schema, if the schema for this table already exists
		if ( $this->contentAlreadyExists($lines, "'$table'"))
			return $this;

		// Get the Migration Schema Template and append it
		$template = 'MigrationSchema' . $template;

		array_insert_at_pattern($lines, [ '// up()' => $this->getTemplate($template, [ 'table' => $table]) ]);
		array_insert_at_pattern($lines, [ '// down()' => "\t\t" . 'Schema::dropIfExists("'.$table.'");'."\n" ]);

		File::delete($path);
		File::put($path, implode("", $lines));

		return $this;
	}

	/**
	 * Create a new Table Seeder for a Model
	 *
	 * @param $name
	 * @param $namespace_root
	 *
	 */
	protected function createTableSeeder($component, $name)
	{
		$model = $name;
		$name  = $component . $name . 'TableSeeder';

		$this->create('Seeder', base_path('database/seeds/'. $name . '.php'), compact('name', 'model'));

		$seeder_path = base_path('database/seeds/DatabaseSeeder.php');

		$content = "\t\t" . '// $this->call(' . $name . '::class);' . "\n";
		$after  = 'unguard()';
		$before = 'reguard()';

		return $this->insertContentIntoFile($seeder_path , $content, $after, $before, $name);
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

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			['table', null, InputOption::VALUE_OPTIONAL, 'Optional Table Name.', null],
		];
	}
} 