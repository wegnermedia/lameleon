<?php namespace Melon\Support\Routing;

use Illuminate\Support\ServiceProvider;

class RoutingServiceProvider extends ServiceProvider {

	/**
	 * Boot up after Registration
	 */
	public function boot()
	{
		$this->bootRouteRepositoryBindings();
		$this->bootRoutePatterns();
	}

	/**
	 * Register Resources and Services
	 */
	public function register()
	{

	}

	/**
	 * Register Route Repository Bindings
	 *
	 * @throws RouteBindingMethodNotExists
	 */
	protected function bootRouteRepositoryBindings()
	{
		// Register in a little comfortable way ;-)
		foreach( $this->getRouteRepositoryBindings() as $param => $abstract)
		{
			$repository = app($abstract);
			$method   = 'routeBinding' . ucfirst($param);

			if ( ! method_exists($repository, $method))
				throw new RouteBindingMethodNotExists($param, $repository);

			$this->app['router']->bind($param, function($value) use ($repository, $method)
			{
				return $repository->$method($value);
			});
		}
	}

	/**
	 * Register all Defined Route Patterns
	 */
	protected function bootRoutePatterns()
	{
		$this->app['router']->patterns($this->getRoutePatterns());
	}


	/**
	 * Get all Repository Bindings for Route Parameters
	 *
	 * @return array
	 */
	protected function getRouteRepositoryBindings()
	{
		return config('melon.route_repository_bindings', []);
	}


	/**
	 * Get all Repository Bindings for Route Parameters
	 *
	 * @return array
	 */
	protected function getRoutePatterns()
	{
		return config('melon.route_patterns', []);
	}

}