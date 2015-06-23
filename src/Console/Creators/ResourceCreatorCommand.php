<?php namespace Melon\Console\Creators;

use Illuminate\Console\Command;

class ResourceCreatorCommand extends Command
{

	/**
	 * The console command name.
	 *
	 * php artisan bake:resource System Languages Language
	 *
	 * @var string
	 */
	protected $name = 'create:resource';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'The Melon Way to create a new resource (model, translations, presenter, migration, seeder & repository)';

	/**
	 *
	 */
	public function fire()
	{
		$this->info('Great, asking for details');

		$resource['name'] = $this->ask('Name');
		$resource['table'] = $this->ask('Table Name');
		$resource['namespace'] = $this->ask('Namespace Root');

		$this->info('Okay, got the basics, now to the details...');

		$resource['translatable'] = $this->confirm('Is translatable');

		vd($resource);
	}

} 