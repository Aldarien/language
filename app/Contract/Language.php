<?php
namespace App\Contract;

use App\Definition\Contract;
use App\Service\Language as LanguageService;

class Language
{
	use Contract;
	
	protected static function newInstance()
	{
		return new LanguageService();
	}
	public static function __callstatic($name, $params)
	{
		if (!method_exists(Language::class, $name)) {
			$instance = self::getInstance();
			if (method_exists($instance, $name)) {
				return call_user_func_array([$instance, $name], $params);
			}
			return null;
		}
		return call_user_func_array([self, $name], $params);
	}
}
?>