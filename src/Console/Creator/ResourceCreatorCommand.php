<?php namespace Melon\Console\Creator;

use File;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
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

		$translatable = $this->confirm('Is it translatable?');

		$this->collectData($component, $name);

		$this->createModel();
		$this->createModelContract();

		$this->createMigration($this->data->get('model.table'));

		$this->createRepository();
		$this->createRepositoryContract();
		$this->addRepositoryBindingToServiceProvider();

		$this->createPresenter();

		$this->createTableSeeder();

		if ( $translatable )
		{
			$this->createModelTranslation();
			$this->createMigration($this->data->get('model_translation.table', 'Translation'));
		}

		$this->info('Whohoooo, Resource created ...');
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