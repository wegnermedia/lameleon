<?php namespace Melon\Support\Events;

use Artisan;
use Illuminate\Events\Dispatcher;
use Melon\Support\Cache\Traits\CachableTrait;

/**
 * Class EventHandler
 */
abstract class EventSubscriber {

	use CachableTrait;

	/**
	 * Get Events to Subscribe to
	 *
	 * @return array
	 */
	abstract protected function getEvents();

	/**
	 * Register the listeners for the subscriber.
	 *
	 * @param  $events
	 * @return array
	 */
	public function subscribe(Dispatcher $events)
	{
		$class = get_called_class();

		foreach( $this->getEvents() as $event )
		{
			$method = $this->getMethodName($event);

			if ( ! method_exists($this, $method) ) continue;

			$events->listen($event, $class . '@' . $method);
		}

		// --- Alternative (später Benchmark machen und dann entscheiden ---

		//		$methods = $this->cache(md5($class.'events'), function(){
		//			return array_filter(get_class_methods($this), function($method){
		//				return preg_match("/^on/u", $method) ? $method : null;
		//			});
		//		});
		//
		//		// Get all Event Handling Methods
		//
		//		$qvents = $this->getQualifiedEvents();
		//
		//		foreach($methods as $method)
		//		{
		//			if ( ! array_key_exists($method, $qvents) ) continue;
		//
		//			$events->listen($qvents[$method], $class . '@' . $method);
		//		}

		// --- Second Alternative ---

//		foreach( $this->getQualifiedEvents() as $method => $event )
//		{
//			if ( ! method_exists($this, $method) ) continue;
//
//			$events->listen($event, $class . '@' . $method);
//		}

	}


	/**
	 * Get an Array of Event Classes and their qualified method on the handlers
	 *
	 * @return mixed
	 */
//	private function getQualifiedEvents()
//	{
//
//		return $this->cache('qualified_events', function(){
//
//			$stack = [];
//
//			$classes = find_classes(Config::get('bake.event_locations', []), app_path());
//
//			// Generate classes on connection with his qualified method
//			foreach( $classes as $class )
//			{
//				$stack[$this->getMethodName($class)] = $class;
//			}
//
//			return $stack;
//		});
//	}


	/**
	 * Get the Handling Method Name
	 *
	 * @param $event
	 *
	 * @return string
	 */
	private function getMethodName($event)
	{
		return 'on' . preg_replace("/Event$/u", "", class_basename($event));
	}


	/**
	 * Call Artisan Command
	 *
	 * @param $command
	 *
	 * @return int
	 */
	protected function call($command)
	{
		return Artisan::call($command);
	}
} 