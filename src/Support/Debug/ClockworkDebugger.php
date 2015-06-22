<?php namespace Melon\Support\Debug;

use Melon\Support\Debug\Contracts\DebuggerService;
use Clockwork;
use Illuminate\Config\Repository;

class ClockworkDebugger implements DebuggerService {

    /**
     * Is Debugging enabled?
     *
     * @var boolean
     **/
    protected $debug = false;

    protected $clockwork;


    /**
     * Only when Debugging is enabled ...
     */
    public function __construct(Repository $config)
    {
        $this->debug = $config->get('app.debug', false);

        // Check for Clockwork Support
        if ( ! class_exists('Clockwork\Clockwork') )
        {
            $this->debug = false;
        }
    }

    /**
     * Start a Timer
     *
     * @return void
     **/
    public function start($name, $descr)
    {
        if ( $this->debug === TRUE )
           Clockwork::startEvent($name,$descr);
    }

    /**
     * Stop a Timer
     *
     * @return void
     **/
    public function stop($name)
    {
        if ( $this->debug === TRUE)
            Clockwork::endEvent($name);
    }


	/**
	 * Just a pointer
	 *
	 * @param $name
	 */
	public function end($name)
	{
		$this->stop($name);
	}

    /**
     * Log to Console
     *
     * @return void
     **/
    public function info($log, $type = 'info')
    {
        if ( $this->debug === TRUE)
            Clockwork::$type($log);
    }
}