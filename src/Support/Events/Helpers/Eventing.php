<?php

if ( ! function_exists('fire') ) {

	function fire($event)
	{
		return event($event);
	}
}
