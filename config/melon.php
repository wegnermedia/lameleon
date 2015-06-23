<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Enable Application Cache
	|--------------------------------------------------------------------------
	*/
	'caching' => true,


    /*
	|--------------------------------------------------------------------------
	| Event Handlers
	|--------------------------------------------------------------------------
	*/
	'event_handlers' => [],

	/*
	|--------------------------------------------------------------------------
	| Event Listeners
	|--------------------------------------------------------------------------
	*/
	'event_listeners' => [],

	/*
	|--------------------------------------------------------------------------
	| Register your View Composers here ...
	|--------------------------------------------------------------------------
	*/
	'view_composers' => [

	],

	/*
	|--------------------------------------------------------------------------
	| All Repository Bindings for Route Parameters
	|--------------------------------------------------------------------------
	*/
	'route_repository_bindings' => [

	],

	/*
	|--------------------------------------------------------------------------
	| Route Patterns for Route Parameters
	|--------------------------------------------------------------------------
	*/
	'route_patterns' => [

	],

	/*
	|--------------------------------------------------------------------------
	| Console Commands to be registered
	|--------------------------------------------------------------------------
	*/
	'commands'  => [
		'command.melon.check' => Melon\Console\CheckCommand::class,
	],

	/*
	|--------------------------------------------------------------------------
	| Service Providers to be registered
	|--------------------------------------------------------------------------
	*/
	'providers' => [
		Barryvdh\Debugbar\ServiceProvider::class,
		Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,
	    Melon\Support\Jobs\JobsServiceProvider::class,
	    Melon\Support\Cache\CacheServiceProvider::class,
	    Melon\Support\Debug\DebugServiceProvider::class,
	    Melon\Support\Events\EventsServiceProvider::class,
	    Melon\Support\Log\LogServiceProvider::class,
	    Melon\Support\Routing\RoutingServiceProvider::class,
	    Melon\Support\Views\ViewsServiceProvider::class,
	],


    /*
    |--------------------------------------------------------------------------
    | Facades
    |--------------------------------------------------------------------------
    */
    'facades' => [
	    // Third Party
	    'Debugbar'      => Barryvdh\Debugbar\Facade::class,

	    // Melon
	    'Bus'       => Melon\Support\Jobs\Facades\Bus::class,
	    'Cacher'    => Melon\Support\Cache\Facades\Cacher::class,
	    'Debugger'  => Melon\Support\Debug\Facades\Debugger::class,
	    'Logger'    => Melon\Support\Log\Facades\Logger::class,
    ],

	/*
	|--------------------------------------------------------------------------
	| Helper paths where files need to be loaded
	|--------------------------------------------------------------------------
	*/
	'helper_paths'   => [

	],

];