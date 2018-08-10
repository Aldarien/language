<?php
namespace App\Facade;

use App\Definition\Facade;
use App\Service\Language as LanguageService;

class Language extends Facade
{
  public static function newInstance()
  {
    return new LanguageService(config('app.language'));
  }
  public static function translate($phrase, $default = null)
  {
    $instance = static::getInstance();
    return $instance->translate($phrase, $default);
  }
}
