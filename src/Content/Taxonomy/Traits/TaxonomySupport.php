<?php namespace Melon\Content\Taxonomy\Traits;

trait TaxonomySupport {

	/**
	 * @return mixed
	 */
	public function tags()
	{
		return $this->morphMany(Melon\Content\Taxonomy\Repository\Tag::class, 'tagable');
	}
} 