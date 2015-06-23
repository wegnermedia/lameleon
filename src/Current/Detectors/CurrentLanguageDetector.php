<?php namespace Melon\Current\Detectors;

use Cookie;
use Melon\Current\Detector;
use Illuminate\Config\Repository as Config;
use Illuminate\Http\Request;
use Illuminate\Session\Store as Session;

class CurrentLanguageDetector extends Detector
{

	protected $lookups = [
		'url',
	    'session',
	    'cookie',
	    'headers',
	    'fallback'
	];

	/**
	 * Storage keys for session and cookies
	 *
	 * @var array
	 */
	protected $keys = [
		'cookie'    => 'my_locale',
		'session'   => 'my.locale'
	];

	/**
	 * @var \Illuminate\Config\Repository
	 */
	private $config;

	/**
	 * @var \Illuminate\Session\Store
	 */
	private $session;

	/**
	 * @var \Illuminate\Http\Request
	 */
	private $request;


	/**
	 * @param \Illuminate\Config\Repository $config
	 * @param \Illuminate\Session\Store     $session
	 * @param \Illuminate\Http\Request      $request
	 */
	function __construct(Config $config, Session $session, Request $request)
	{
		$this->config = $config;
		$this->session = $session;
		$this->request = $request;
	}


	/**
	 * This is what happens, wenn the detection passes
	 *
	 * @param $lookup
	 *
	 * @return mixed
	 */
	protected function handleDetectionComplete($lookup)
	{
		debugger()->info('Language detected: '.$this->detected->slug);

		Cookie::queue($this->keys['cookie'], $this->detected->slug);
		$this->session->set($this->keys['session'], $this->detected->slug);
		$this->config->set('app.locale', $this->detected->slug);

		return $this->detected;
	}

	/**
	 * Is this a valid locale slug?
	 *
	 * if so, return the detected locale, if not
	 * just return a false
	 *
	 * @param $slug
	 * @return bool
	 */
	protected function isValidLanguage($slug)
	{
		return (bool) array_key_exists($slug, $this->config->get('melon.locales',[]));
	}

	/**
	 * Try to get a locale by it's request slug
	 *
	 * @return bool
	 */
	protected function tryUrl()
	{
		if ( $slug = $this->request->segment(1, false) )
			return $this->isValidLanguage($slug);

		return false;
	}

	/**
	 * Try to get a locale slug from current session
	 *
	 * @return bool
	 */
	protected function trySession()
	{
		if ( $slug = session($this->keys['session'], false) )
		{
			return $this->isValidLanguage($slug);
		}

		return false;
	}

	/**
	 * Try to get a locale slug from stored cookie
	 *
	 * @return bool
	 */
	protected function tryCookie()
	{
		if ( $slug = Cookie::get($this->keys['cookie'], false) )
			return $this->isValidLanguage($slug);

		return false;
	}

	/**
	 * Try to get a prefered Locale from Accept-Headers
	 *
	 * @return bool
	 */
	protected function tryHeaders()
	{
		$language_list = $this->job->languages->lists('slug');

		if ( $slug = $this->request->getPreferredLanguage($language_list) )
			return $this->isValidLanguage($slug);

		return false;
	}

	/**
	 * Okay, take the default ;-)
	 *
	 * @return mixed
	 */
	protected function tryFallback()
	{
		return $this->config->get('app.fallback_locale');
	}
}