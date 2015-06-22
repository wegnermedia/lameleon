<?php

use Melon\Support\Debug\Contracts\DebuggerService;

if ( ! function_exists('debugger') ) {

	/**
	 * @return DebuggerService
	 */
	function debugger($info = null, $type = 'info')
	{
		if ( $info )
			return app(DebuggerService::class)->info($info, $type);

		return app(DebuggerService::class);
	}
}