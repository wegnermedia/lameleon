<?php namespace Melon\Support\Events;

use Illuminate\Support\ServiceProvider;

class EventsServiceProvider extends ServiceProvider {

	/**
	 * Boot up after Registration
	 */
	public function boot()
	{
		$this->bootEventListeners();
		$this->bootEventHandlers();
	}

	/**
	 * Register Resources and Services
	 */
	public function register()
	{

	}

	/**
	 * Register all System wide Event Handlers
	 *
	 * @param $handlers
	 */
	protected function bootEventHandlers()
	{
		foreach( $this->getEventHandlers() as $handler)
		{
			$this->app['events']->subscribe($handler);
		}
	}


	/**
	 * Register all Event Listeners Here ...
	 */
	protected function bootEventListeners()
	{
		foreach( $this->getEventListeners() as $event => $handlers )
		{
			foreach($handlers as $handler)
			{
				$this->app['events']->listen($event, $handler);
			}
		}
	}

	/**
	 * Get all Event Handlers
	 *
	 * @return array
	 */
	function getEventHandlers()
	{
		return config('cameleon.event_handlers',[]);
	}


	/**
	 * Get all Event Listeners
	 *
	 * @return array
	 */
	function getEventListeners()
	{
		return config('cameleon.event_listeners',[]);
	}



}