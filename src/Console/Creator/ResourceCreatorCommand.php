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
		$name = $this->argument('name');
		$table = $this->toTable($name, $this->option('table'));

		$translatable = $this->confirm('Is it translatable?');

		$this->createModel($name, $table);
		$this->createRepository($name);
		$this->createPresenter($name);
		$this->createMigration($name, $table, 'Default');
		$this->createTableSeeder($name);

		if ( $translatable )
		{
			$this->createModel($name . 'Translation', $this->toTranslationTable($table));
			$this->createMigration($name, $this->toTranslationTable($table), 'Translation');
		}

		$this->info('Whohoooo, Resource created ...');
	}

	/**
	 * @param $name
	 * @param $table
	 *
	 * @return \Melon\Console\Creator\ResourceCreatorCommand
	 */
	protected function createModel($name, $table)
	{
		return $this->create('Model', app_path("Repositories/$name.php"), compact('name', 'table'));
	}

	/**
	 * Create a new repository class
	 *
	 * @param $name
	 *
	 * @return $this
	 */
	protected function createRepository($name)
	{
		$model = $name;
		$repository = $name . 'Repository';

		return $this->create('Repository', app_path("Repositories/$repository.php"), compact('repository', 'model'));
	}

	/**
	 * Create a new presenter class
	 *
	 * @param $name
	 *
	 * @return $this
	 */
	protected function createPresenter($name)
	{
		$name = $name . 'Presenter';

		return $this->create('Presenter', app_path("Presenters/$name.php"), compact('name'));
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
	protected function createMigration($name, $table, $template = 'Default')
	{
		$filename = "create_{$name}_tables";

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
	 * @return \Melon\Console\Creators\Traits\MelonCreatorTrait
	 */
	protected function createTableSeeder($name)
	{
		$model = $name;
		$name  = $name . 'TableSeeder';

		$this->create('Seeder', base_path('database/seeds/'. $name . '.php'), compact('name', 'model'));

		$seeder_path = base_path('database/seeds/DatabaseSeeder.php');

		$content = "\t\t" . '// $this->call("' . $name . '");' . "\n";
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
			['name', InputArgument::REQUIRED, 'Name of the resource (e.g. Language or Order)'],
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