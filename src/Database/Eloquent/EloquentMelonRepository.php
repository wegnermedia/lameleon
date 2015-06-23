<?php namespace Melon\Database\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Melon\Cache\Traits\CacheSupport;

trait EloquentMelonRepository {

	use CacheSupport;

	/**
	 * Start the Query Builder with Relations
	 *
	 * @param array $with
	 *
	 * @return mixed
	 */
	protected function make(array $with = [])
	{
		return $this->model->with(array_unique($this->with, $with));
	}

	/**
	 * @param array $relations
	 *
	 * @return mixed
	 */
	public function first(array $with = [])
	{
		return $this->make($with)->first();
	}

	/**
	 * Get all with relations
	 *
	 * @param array $relations
	 *
	 * @return mixed
	 */
	public function all(array $with = [])
	{
		return $this->make($with)->get();
	}

	/**
	 * Find Entity by a given key/value with custom Relations
	 *
	 * @param       $key
	 * @param       $value
	 * @param array $relations
	 *
	 * @return mixed
	 */
	public function findBy($key, $value, array $with = [])
	{
		return $this->make($with)->where($key, $value)->first();
	}

	/**
	 * Find Entity by a given ID + custom Relations
	 *
	 * @param       $id
	 * @param array $relations
	 *
	 * @return mixed
	 */
	public function find($id, array $with = [])
	{
		return $this->findBy('id', $id, $with);
	}

	/**
	 * Build up a related Query for fetching by a related key+value
	 *
	 * @param string $relation
	 * @param string $key
	 * @param string $value
	 * @param array $with
	 * @param string $operator
	 *
	 * @return Builder
	 */
	protected function related($relation, $key, $value, array $with = [], $operator = '=')
	{
		return $this->make($with)->whereHas($relation, function($query) use ($key, $value, $operator) {
			$query->where($key, $operator, $value);
		});
	}

	/**
	 * Find the first by a related table key+value
	 *
	 * @param string $relation
	 * @param string $key
	 * @param string $value
	 * @param array $with
	 * @param string $operator
	 *
	 * @return mixed
	 */
	public function firstRelated($relation, $key, $value, array $with = [], $operator = '=')
	{
		return $this->related($relation, $key, $value, $with, $operator)->first();
	}

	/**
	 * Get all related table key+value
	 *
	 * @param string $relation
	 * @param string $key
	 * @param string $value
	 * @param array $with
	 * @param string $operator
	 *
	 * @return mixed
	 */
	public function allRelated($relation, $key, $value, array $with = [], $operator = '=')
	{
		return $this->related($relation, $key, $value, $with, $operator)->get();
	}

	/**
	 * Update an Relationship on a given model
	 *
	 * (BelongsTo only)
	 *
	 * @param $relation
	 * @param $new
	 *
	 * @return mixed
	 */
	public function updateBelongsTo($entity, $relation, $new)
	{
		if ( ! method_exists($entity, $relation ))
			return $entity;

		$entity->$relation()->associate($new);

		$entity->save();

		return $this->find($entity->id);
	}


	/**
	 * Get only Translated
	 *
	 * @param $data
	 *
	 * @return array|null
	 */
	public function onlyTranslated($data)
	{
		if ( empty($data) )
			return null;

		$stack = [];

		foreach( $data as $item)
		{
			if ( $item->translation )
				$stack[] = $item;
		}

		return $stack;
	}

} 