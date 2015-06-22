<?php namespace Melon\Support\Debug\Contracts;

interface DebuggerService {

	/**
	 * @param $log
	 *
	 * @return mixed
	 */
	public function info($log, $type = 'info');


	/**
	 * @param $name
	 * @param $log
	 *
	 * @return mixed
	 */
	public function start($name, $label);


	/**
	 * @param $name
	 *
	 * @return mixed
	 */
	public function stop($name);


	/**
	 * @param $name
	 *
	 * @return mixed
	 */
	public function end($name);

}