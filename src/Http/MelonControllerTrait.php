<?php namespace Melon\Http;

use Illuminate\Foundation\Validation\ValidatesRequests;

trait MelonControllerTrait
{
	use ValidatesRequests;

	/**
	 * For a litte convinience with typing the full view name over and over.
	 *
	 * @var string
	 */
	public $viewPrefix = '';


	/**
	 * @param       $name
	 * @param array $params
	 * @param bool  $prefix
	 *
	 * @return \Illuminate\View\View
	 */
	protected function view($name, array $params = [], $prefix = true)
	{
		if ( $prefix )
			$name = $this->viewPrefix . '.' . $name;

		return view($name, $params);
	}

	/**
	 * Melon Redirect Helper
	 *
	 * @param       $name
	 * @param array $params
	 * @param null  $locale
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	protected function redirect($name, $params = [], $locale = null)
	{
		return redirect(melon_route($name, $params, $locale));
	}

} 