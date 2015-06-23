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
    | Locales
    |--------------------------------------------------------------------------
    |
    | Here is a list of current available languages, if it's a multi-language
    | application. The key is the unique language slug + the value equals
    | the language id in Database. This should be updated on each request.
    |
    */
    'locales' => [
	    'de' => 1,
    ],

	/*
	|--------------------------------------------------------------------------
	| Models
	|--------------------------------------------------------------------------
	|
	| For the ultimative experience and comfortable usage of model helpers,
	| you'll need to setup some models here ... These will be available
	| as a constant via MELON_MODEL_USER everywhere in application.
	|
	*/
    'models'    => [
	    'user'          => Bake\System\Users\Repository\User::class,
        'language'      => null,
        'tag'           => null
    ],

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
		'command.melon.create.resource' => Melon\Console\Creators\ResourceCreatorCommand::class,
	],

	/*
	|--------------------------------------------------------------------------
	| Service Providers to be registered
	|--------------------------------------------------------------------------
	*/
	'providers' => [
		Barryvdh\Debugbar\ServiceProvider::class,
		Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,
	    Melon\Jobs\JobsServiceProvider::class,
	    Melon\Cache\CacheServiceProvider::class,
	    Melon\Debug\DebugServiceProvider::class,
	    Melon\Events\EventsServiceProvider::class,
	    Melon\Log\LogServiceProvider::class,
	    Melon\Routing\RoutingServiceProvider::class,
	    Melon\Views\ViewsServiceProvider::class,
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
	    'Bus'       => Melon\Jobs\Facades\Bus::class,
	    'Cacher'    => Melon\Cache\Facades\Cacher::class,
	    'Debugger'  => Melon\Debug\Facades\Debugger::class,
	    'Logger'    => Melon\Log\Facades\Logger::class,
    ],

	/*
	|--------------------------------------------------------------------------
	| Helper paths where files need to be loaded
	|--------------------------------------------------------------------------
	*/
	'helper_paths'   => [

	],

];