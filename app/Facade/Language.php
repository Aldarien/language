<?php
namespace App\Facade;

use App\Service\Language as LanguageService;

class Language
{
  protected static $instance;

  private function __construct() {}
  public static function newInstance()
  {
    $config = [
      'language' => config('app.language'),
      'language_files' => config('locations.languages')
    ];
    return new LanguageService($config);
  }
  public static function getInstance()
  {
    if (!isset(self::$instance) or self::$instance == null) {
      self::$instance = static::newInstance();
    }
    return self::$instance;
  }
  public static function translate($phrase, $default = null)
  {
    $instance = static::getInstance();
    return $instance->translate($phrase, $default);
  }
}
