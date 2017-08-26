<?php
use PHPUnit\Framework\TestCase;

class LanguageTest extends TestCase
{
	public function setUp()
	{
		mkdir(root() . '/resources');
		mkdir(root() . '/resources/languages');
		file_put_contents(root() . '/resources/languages/es.json', json_encode(['Hello' => 'Hola', 'Hello World' => 'Hola Mundo']));
	}
	public function tearDown()
	{
		unlink(root() . '/resources/languages/es.json');
		rmdir(root() . '/resources/languages');
		rmdir(root() . '/resources');
	}
	
	public function testLanguage()
	{
		$word = 'Hello';
		$phrase = 'Hello World';
		
		$this->assertEquals('Hola', lang($word));
		$this->assertEquals('Hola Mundo', lang($phrase));
	}
}
?>