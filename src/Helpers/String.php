<?php

if ( ! function_exists('random_string'))
{
    /**
     * Get a random string
     *
     * @param int   $length
     * @param array $exclude
     *
     * @return string
     */
    function random_string($length = 10, $exclude = [])
    {
        $stack = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // Sometimes, you may wanna exclude some Chars - here is your chance ...
        if ( ! empty($exclude) )
            $stack = str_replace($exclude, '', $stack);

        $stack = str_repeat($stack, 10);

        $stack_length = strlen($stack) - 1;

        $string = '';

        for ($i = 0; $i < $length; $i++)
        {
            $string .= $stack[ rand( 0, $stack_length ) ];
        }

        return $string;
    }
}

if ( ! function_exists('token') ) {

    function token($table, $field, $length = 60)
    {
        $unique = false;
		$token = '';

        while(! $unique )
        {
            $token = random_string($length);
            $unique = DB::table($table)->where($field, $token)->first() ? false : true;
        }

        return $token;
    }
}

if ( ! function_exists('to_case') ) {

	function to_case($string, $by = ['-','_','.','/','\\'])
	{
		return str_replace(' ','', ucwords(str_replace($by,' ', $string)));
	}
}
