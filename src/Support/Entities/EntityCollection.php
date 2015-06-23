<?php namespace Melon\Support\Entities;

use Illuminate\Support\Collection;

/**
 * Class EntityCollection
 *
 * @package Bake\Foundation\Database\Foundation\Entities
 */
class EntityCollection extends Collection {

	protected $original = [];

	/**
	 * @param array|mixed $collection
	 */
	function __construct($collection)
	{
		$this->original = $collection;

		$stack = [];

		$entity = $this->getEntityClass();

		foreach ( $collection as $item )
		{
			$stack[] = new $entity($item);
		}

		parent::__construct($stack);
	}


	/**
	 * @return mixed
	 */
	protected function getEntityClass()
	{
		if ( property_exists($this, 'entityClass') ) return $this->entityClass;

		return preg_replace("/Collection$/u", '', get_called_class());
	}


	/**
	 * A better List method for EntityCollections
	 *
	 * @param string $value
	 * @param null   $key
	 *
	 * @return array
	 */
	public function lists($value, $key = null)
	{
		$list = [];

		if ( $key ) {
			foreach($this->items as $entity) {
				$list[$entity->$key] = $entity->$value;
			}

			return $list;
		}

		foreach($this->items as $entity) {
			$list[] = $entity->$value;
		}

		return $list;
	}


	public function toArray()
	{
		return $this->original;
	}

} 