<?php
namespace App\Facade;

use App\Definition\Facade;
use App\Service\Language as LanguageService;

class Language extends Facade
{
  public static function newInstance()
  {
    $config = [
      'language' => config('app.language'),
      'language_files' => config('locations.languages')
    ];
    return new LanguageService($config);
  }
  public static function translate($phrase, $default = null)
  {
    $instance = static::getInstance();
    return $instance->translate($phrase, $default);
  }
}
