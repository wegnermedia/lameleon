<?php namespace Melon\Support\Database\Eloquent;

use Illuminate\Database\Eloquent\Builder;

trait MelonModel {

	/**
	 * Auto Transform Dates to a Carbon Object
	 *
	 * @return array
	 **/
	public function getDates()
	{
		return [
			'created_at',
			'updated_at',
			'deleted_at',
			'published_at',
			'registered_at',
			'confirmed_at',
			'blocked_at',
		];
	}

	/**
	 * Get a property of the object
	 *
	 * @param      $key
	 * @param null $default
	 *
	 * @return null
	 */
	public function property($key, $default = null)
	{
		return object_get($key, $default);
	}

	/**
	 * Get an enabled state of an option
	 *
	 * @param      $name
	 * @param bool $default
	 *
	 * @return bool|mixed
	 */
	public function isEnabled($name, $default = false)
	{
		$key = 'enable_'.$name;

		if ( ! isset($this->$key) )
			return $default;

		return $this->$key;
	}


	/**
	 * @param      $name
	 * @param      $state
	 * @param bool $save
	 *
	 * @return $this
	 */
	public function switchEnabled($name, $state, $save = true)
	{
		$key = 'enable_'.$name;

		// Just a little bit protection
		if ( ! is_bool($state) )
			return $this;

		$this->$key = $state;

		// Save immediatly
		if ( $save )
			$this->save();

		return $this;
	}

	/**
	 * @param      $name
	 * @param bool $save
	 *
	 * @return $this
	 */
	public function enable($name, $save = true)
	{
		return $this->switchEnabled($name, true, $save);
	}

	/**
	 * @param      $name
	 * @param bool $save
	 *
	 * @return $this
	 */
	public function disable($name, $save = true)
	{
		return $this->switchEnabled($name, false, $save);
	}

	/**
	 * @return Builder
	 */
	public function owner()
	{
		return $this->belongsTo(Melon\System\Users\User::class, 'owner_id');
	}


	/**
	 * @return Builder
	 */
	public function editor()
	{
		return $this->belongsTo(Melon\System\Users\User::class, 'editor_id');
	}


	/**
	 * Active = true
	 *
	 * @return Builder
	 **/
	public function scopeActive($query)
	{
		return $query->where("{$this->table}.is_active", '=', true);
	}

	/**
	 * Active = false
	 *
	 * @return Builder
	 **/
	public function scopeInactive($query)
	{
		return $query->where("{$this->table}.is_active", '=', false);
	}


	/**
	 * Default = true
	 *
	 * @return Builder
	 **/
	public function scopeDefault($query)
	{
		return $query->where("{$this->table}.is_default", '=', true);
	}

	/**
	 * Published
	 *
	 * @return Builder
	 **/
	public function scopePublished($query)
	{
		return $query->where("{$this->table}.published_at", '!=', null);
	}

	/**
	 * Unpublished
	 *
	 * @return Builder
	 **/
	public function scopeUnpublished($query)
	{
		return $query->where("{$this->table}.published_at", '=', null);
	}

	/**
	 * Confirmed
	 *
	 * @return Builder
	 **/
	public function scopeConfirmed($query)
	{
		return $query->where("{$this->table}.confirmed_at", '!=', null);
	}

	/**
	 * UnConfirmed
	 *
	 * @return Builder
	 **/
	public function scopeUnconfirmed($query)
	{
		return $query->where("{$this->table}.confirmed_at", '=', null);
	}

	/**
	 * Banned / Blocked
	 *
	 * @return Builder
	 **/
	public function scopeBanned($query)
	{
		return $query->where("{$this->table}.banned_at", '!=', null);
	}

	/**
	 * Banned / Blocked
	 *
	 * @return Builder
	 **/
	public function scopeNotBanned($query)
	{
		return $query->where("{$this->table}.banned_at", '=', null);
	}

	/**
	 * Registered
	 *
	 * @return Builder
	 **/
	public function scopeRegistered($query)
	{
		return $query->where("{$this->table}.registered_at", '!=', null);
	}

	/**
	 * UnRegistered
	 *
	 * @return Builder
	 **/
	public function scopeUnregistered($query)
	{
		return $query->where("{$this->table}.registered_at", '=', null);
	}


	/*
	|--------------------------------------------------------------------------
	| Many Through Many Relation
	|--------------------------------------------------------------------------
	*/
	public function manyThroughMany($related, $through, $firstKey, $secondKey, $pivotKey)
	{
		$model = new $related;
		$table = $model->property('table');
		$throughModel = new $through;
		$pivot = $throughModel->property('table');

		//		return Permission::join('System__Permission__Role.role_id', '=', $this->role_id);

		return $model
			->join($pivot, $pivot . '.' . $pivotKey, '=', $table . '.' . $secondKey)
			->select($table . '.*')
			->where($pivot . '.' . $firstKey, '=', $this->id);
	}

} 