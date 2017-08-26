<?php
namespace App\Service;

use App\Contract\Session;

class Language
{
	protected $lang;
	protected $translations;
	
	public function __construct()
	{
		$lang = null;
		if (!headers_sent()) {
			$lang = Session::get('App', 'lang');
		}
		if ($lang == null) {
			$lang = config('app.default_language');
			if (!headers_sent()) {
				Session::set('App', 'lang', $lang);
			}
		}
		$this->lang = $lang;
		$this->load($lang);
	}
	protected function load(string $lang)
	{
		$dir = config('locations.languages');
		$filename = $dir . DIRECTORY_SEPARATOR . $lang . '.json';
		if (!file_exists($filename)) {
			$this->createFile($lang);
		}
		$file = trim(file_get_contents($filename));
		$data = json_decode($file, true);
		$this->translations = [];
		foreach ($data as $base_word => $translation) {
			$this->addWord($base_word, $translation);
		}
	}
	public function translate(string $base)
	{
		if (isset($this->translations[$base])) {
			return $this->translations[$base];
		}
		return false;
	}
	public function addWord(string $base_word, string $translation)
	{
		$this->translations[$base_word] = $translation;
	}
	public function save()
	{
		$dir = config('locations.languages');
		$filename = $dir . DIRECTORY_SEPARATOR . $this->lang . '.json';
		$data = json_encode($this->translations, JSON_PRETTY_PRINT);
		return file_put_contents($filename, $data);
	}
	public function changeLang(string $lang)
	{
		$this->reset();
		$this->lang = $lang;
		$this->load($lang);
	}
	protected function createFile(string $lang)
	{
		$this->reset();
		$default = config('app.default_language');
		$this->load($default);
		$this->lang = $lang;
		$this->save();
	}
	protected function reset()
	{
		$this->lang = null;
		$this->translations = null;
	}
	public function available()
	{
		$dir = config('locations.languages');
		$files = glob($dir . '/*.json');
		$output = [];
		foreach ($files as $filename) {
			$info = pathinfo($filename);
			$output []= $info['filename'];
		}
		return $output;
	}
}
?>