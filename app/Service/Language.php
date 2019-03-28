<?php
namespace App\Service;

use Symfony\Component\Yaml\Yaml;
use Stringy\Stringy;

class Language
{
  protected $language;
  protected $location;
  protected $phrases;

  public function __construct($configuration)
  {
    $this->language = $configuration['language'];
    $this->location = $configuration['language_files'];
    $this->loadFiles();
  }
  public function available()
  {
		$output = [];
		$files = glob($this->location . '/' . $this->language . '/*.*');
		foreach ($files as $file) {
			$info = new \SplFileInfo($file);
			$output []= $info->getBasename('.' . $info->getExtension());
		}
		return $output;
  }
  protected function loadFiles()
  {
    $files = glob($this->location . '/' . $this->language . '/*.*');
    foreach ($files as $file) {
      $data = $this->loadFile($file);
      $this->add($data);
    }
  }
  protected function loadFile($filename)
  {
    $info = pathinfo($filename);
    switch ($info['extension']) {
      case 'yaml':
		    $filename = str_replace('yaml', 'yml', $filename);
  	  case 'yml':
        return $this->loadYAML($filename);
      case 'json':
        return $this->loadJSON($filename);
    }
  }
  protected function loadYAML($filename)
  {
    return Yaml::parseFile($filename);
  }
  protected function loadJSON($filename)
  {
    return json_decode($filename);
  }
  public function add($origin, $translation = null)
  {
    if (is_array($origin)) {
      foreach ($origin as $o => $t) {
        $this->add($o, $t);
      }
    } else {
      $this->phrases[$origin] = $translation;
    }
  }
  public function translate($phrase, $default = null)
  {
    $upper = false;
    $first = false;
    if (Stringy::create($phrase)->hasUpperCase()) {
      $first = true;
      $upper = true;
    }
    if (!Stringy::create($phrase)->slice(1)->hasUpperCase()) {
      $upper = false;
    }
    $phrase = strtolower($phrase);
    if (!isset($this->phrases[$phrase])) {
      if ($default == null) {
        if ($this->language != 'en') {
          $configuration = ['language' => 'en', 'language_files' => $this->location];
          $def = new Language($configuration);
          $t = $def->translate($phrase, '');
        } else {
          $t = $phrase;
        }
      } else {
        $t = $default;
      }
    } else {
      $t = $this->phrases[$phrase];
    }

    if ($first) {
      $t = ucfirst($t);
    } elseif ($upper) {
      $t = ucwords($t);
    }
    return $t;
  }
}
