<?php namespace Lameleon\Support\Views;

use Illuminate\Support\ServiceProvider;

class ViewsServiceProvider extends ServiceProvider {

	/**
	 * Boot up after Registration
	 */
	public function boot()
	{
		$this->app['view']->composers($this->getViewComposers());
	}

	/**
	 * Register Resources and Services
	 */
	public function register()
	{

	}

	/**
	 * @return array
	 */
	public function getViewComposers()
	{
		return config('lameleon.view_composers');
	}
}