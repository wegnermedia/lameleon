<?php

if ( ! function_exists('translate_eloquent_model') ) {
	
	function translate_eloquent_model($translator, $data, $original, $owner = null, $editor = null)
	{
		if ( is_object($original))
			$original = $original->id;

		if ( is_object($owner) )
			$owner = $owner->id;

		if ( is_object($editor) )
			$editor = $editor->id;

		$language_repo = config('lameleon.');

		foreach($data as $slug => $translation_data)
		{
			if ( ! $language = Language::where('slug', $slug)->first() )
				continue;

			$translation = (new $translator)->create($translation_data);

			$translation->language_id = $language->id;
			$translation->original_id = $original;
			$translation->owner_id = $owner;
			$translation->editor_id = $editor;

			$translation->save();

		}
	}
}

if ( ! function_exists('make_translation_data') ) {

	function make_translation_data(array $data)
	{
		$languages = Language::all()->lists('slug');

		$default = array_get($data, 'en', array_get($data,'de',[]));

		foreach($languages as $language)
		{
			if ( array_key_exists($language, $data) )
				continue;

			$data[$language] = $default;
		}

		return $data;
	}
}

