<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Console Commands to be registered
	|--------------------------------------------------------------------------
	*/
	'commands'  => [
		'command.rocket.launch' => Wegnermedia\Rocket\Console\LaunchCommand::class,
	],

	/*
	|--------------------------------------------------------------------------
	| Service Providers to be registered
	|--------------------------------------------------------------------------
	*/
	'providers' => [
		Barryvdh\Debugbar\ServiceProvider::class,
		Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,
	],


    /*
    |--------------------------------------------------------------------------
    | Facades
    |--------------------------------------------------------------------------
    */
    'facades' => [
	    'Debugbar'  => Barryvdh\Debugbar\Facade::class,
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