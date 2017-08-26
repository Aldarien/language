<?php
function lang($base_phrase, $default_value = null) {
	$translation = App\Contract\Language::translate($base_phrase);
	if ($translation) {
		return $translation;
	}
	if ($default_value == null) {
		$default_value = $base_phrase;
	}
	App\Contract\Language::addWord($base_phrase, $default_value);
	App\Contract\Language::save();
	return $default_value;
}
?>