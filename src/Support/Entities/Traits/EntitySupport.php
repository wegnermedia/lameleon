<?php namespace Melon\Support\Entities\Traits;

use Melon\Support\Entities\Entity;
use Melon\Support\Entities\EntityCollection;

trait EntitySupport {

	/**
	 * Get the Entity Class
	 *
	 * @return mixed
	 */
	protected function getEntityClass()
	{
		return $this->entity;
	}

	/**
	 * Get the Entity Collection Class
	 *
	 * @return mixed
	 */
	protected function getEntityCollectionClass()
	{
		return $this->entityCollection;
	}

	/**
	 * Render Result to Detedicated Entity
	 *
	 * @param $result
	 *
	 * @return Entity|null
	 */
	public function renderEntity($result)
	{
		$class = $this->getEntityClass();
		return $result ? new $class($result->toArray()) : null;
	}

	/**
	 * Render Results to Dedicated EntityCollection
	 *
	 * @param $results
	 *
	 * @return EntityCollection|null
	 */
	public function renderEntities($results)
	{
		$class = $this->getEntityCollectionClass();
		return $results ? new $class($results->toArray()) : null;
	}

} 