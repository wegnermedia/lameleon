<?php namespace Melon\Support\Entities;

/**
 * Class Entity
 */
class Entity {

	/**
	 * @var null
	 */
	protected $languageID = null;

	/**
	 * @var bool
	 */
	protected $translatable = false;

	/**
	 * @var array
	 */
	protected $data = [];

	/**
	 * @param array $data
	 */
	function __construct(array $data = null)
	{
		$data = is_null($data) ? [] : $data;
		$this->data = $this->renderEntity($data);
	}

	/**
	 * Render down the Entity with all his named Relations
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	protected function renderEntity(array $data)
	{
		// Get the default relations
		$relations = $this->getRelations();

		// Add Translatable relations if nessesary ...
		if ( array_has($data, 'translations'))
		{
			$this->translatable = true;

			if ( ! array_has($relations, 'translations') )
				$relations['translations'] = get_called_class().'TranslationCollection';

			if ( ! array_has($relations, 'translation') )
				$relations['translation'] = get_called_class().'Translation';
		}

		// Add Original relations if nessesary ...
		if ( array_has($data, 'original'))
		{
			$this->translatable = true;

			if ( ! array_has($relations, 'original') )
				$relations['original'] = preg_replace("/Translation$/u", "", get_called_class() );
		}

		foreach( $relations as $name => $class )
		{
			// Create the new Entity or Entity Collection and pass
			// the array of attributes, easy peasy ...
			$data[$name] = new $class(array_get($data, $name, []));
		}

		return $data;
	}


	/**
	 * Get the Relations, overwrite the method in the Entity Class
	 *
	 * @return array
	 */
	protected function getRelations()
	{
		return [];
	}

	/**
	 * Get a Value form the Stack
	 *
	 * @param      $key
	 * @param null $default
	 *
	 * @return mixed
	 */
	public function get($key, $default = null)
	{
		return array_get($this->data, $key, $default);
	}


	/**
	 * Does this entity has a given key?
	 *
	 * @param      $key
	 * @param bool $true
	 * @param bool $false
	 *
	 * @return bool
	 */
	public function has($key, $true = true, $false = false)
	{
		return array_has($this->data, $key) ? $true : $false;
	}

	/**
	 * Returns usually a boolean for the question if a value is true
	 * But you can also provide a return -> for HTML class attributes
	 * $article->is('active', 'active'); returns 'active' if true.
	 *
	 * @param      $key
	 * @param bool $return
	 *
	 * @return bool|string
	 */
	public function is($key, $true = true, $false = false)
	{
		// normalize key and prefix it
		$key = 'is_' . preg_replace("/^is_/u", "", $key);

		if ( ! $result = $this->get($key, false) )
			return $false;

		return $true;
	}


	/**
	 * Get the current locale translation Entity for this Entity
	 *
	 */
	public function translation()
	{
		if ( $translation = $this->get('translation', false) )
			return $translation;

		$translation_entity = get_called_class(). 'Translation';

		return new $translation_entity([]);
	}


	/**
	 * Get all Translation Entites or just take a localed one ...
	 *
	 * @param null $locale
	 */
	public function translations($locale = null)
	{
		if ( ! $translations = $this->get('translations', false))
		{
			$translation_collection = get_called_class().'TranslationCollection';
			$translations = new $translation_collection([]);
		}

		if ( is_null($locale) )
			return $translations;

		// Get the Language ID for the given locale
		$id = language($locale)->property('id');

		$results = $translations->filter(function($translation) use ($id){
			if($translation->language_id == $id) return true;
		});

		return $results->first();
	}


	/**
	 * Try to resolve the requested param with the original getter
	 *
	 * @param $name
	 *
	 * @return mixed
	 */
	function __get($name)
	{
		$result = $this->get($name, 'NOVALUE');

		if ( $result != 'NOVALUE' )
			return $result;

		// Maybe an is() call
		return $this->is($name);
	}

} 