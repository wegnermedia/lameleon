<?php namespace Melon\Console;

use Illuminate\Console\Command;

class RefreshCommand extends Command
{
	/**
	 * The console command name.
	 *
	 * php artisan
	 *
	 * @var string
	 */
	protected $name = 'melon:refresh';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Refresh the whole DB';

	/**
	 *
	 */
	public function fire()
	{
		if ( ! $this->confirm('Are you sure? [y|n]') )
			return null;

		$this->info('-> Start Installing ...');

		try
		{
			$this->call('migrate:rollback');
			$this->comment('Migrations Rolled back.');
		}
		catch (\Exception $e)
		{
			$this->call('migrate:install');
		}

		$this->call('migrate');
		$this->comment('Migration Work: DONE!');

		$this->info('-> Start Seeding Process ...');
		$this->call('db:seed');
		$this->comment('Seeding Work: DONE!');

		$this->info('-> Clearing Caches ...');
		$this->call('cache:clear');

		$this->info('');
		$this->info('-> Whohooo, you got a fresh install ...');
	}
} 