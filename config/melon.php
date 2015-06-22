<?php

return [

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
	    Melon\Support\Routing\RoutingServiceProvider::class,
	    Melon\Support\Events\EventsServiceProvider::class,
	    Melon\Support\Views\ViewsServiceProvider::class,
	],


    /*
    |--------------------------------------------------------------------------
    | Facades
    |--------------------------------------------------------------------------
    */
    'facades' => [
	    'Debugbar'      => Barryvdh\Debugbar\Facade::class,
	    'Bus'    => Melon\Support\Jobs\Facades\Bus::class,
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
	| Location Language Support for easy translation management
	|--------------------------------------------------------------------------
	*/
	'language'  => [
//		'repository'    => App\System\Location\Repository\LanguageRepository::class,
//		'model'         => App\System\Location\Repository\Language::class,
	],



	/*
	|--------------------------------------------------------------------------
	| Helper paths where files need to be loaded
	|--------------------------------------------------------------------------
	*/
	'helper_paths'   => [

	],

];