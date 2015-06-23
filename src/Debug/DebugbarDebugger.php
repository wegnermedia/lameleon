<?php namespace Melon\Debug;

use Debugbar;
use Illuminate\Config\Repository;
use Melon\Debug\Contracts\DebuggerService;

class DebugbarDebugger implements DebuggerService {

	/**
	 * Is Debugging enabled?
	 *
	 * @var boolean
	 **/
	protected $debug = false;


	/**
	 * Only when Debugging is enabled ...
	 */
	public function __construct(Repository $config)
	{
		if ( $config->get('app.debug', false) || $config->get('debugbar.enabled'))
			$this->debug = true;
	}

	/**
	 * @param $log
	 *
	 * @return mixed
	 */
	public function info($log, $type = 'info')
	{
		if ( $this->debug )
			Debugbar::$type($log);
	}


	/**
	 * @param $name
	 * @param $label
	 *
	 * @return string
	 */
	public function start($name, $label)
	{
		if ( $this->debug )
			Debugbar::startMeasure($name, $label);

		return $name;
	}


	/**
	 * @param $name
	 *
	 * @return mixed
	 */
	public function stop($name)
	{
		if ( $this->debug )
			Debugbar::stopMeasure($name);
	}


	/**
	 * @param $name
	 *
	 * @return mixed
	 */
	public function end($name)
	{
		$this->stop($name);
	}
}