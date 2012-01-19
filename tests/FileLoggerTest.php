<?php
require_once('../lib/Aerodynamic.php');

class FileLoggerTest extends UnitTest
{
	public function setUpTest()
	{
		
	}

	public function tearDownTest()
	{
		
	}

	public function testFileLogging()
	{
		$refObj = new ReflectClass('Aerodynamic', null);
		$ad = $refObj->getReflection();
		$ad->registerHandler(\Matthewshafer\Aerodynamic\Loggers\File::init('test.txt'), 0);

		//var_dump($ad->log("loggingTest", Aerodynamic::EMERG));
		//assert($ad->log("loggingTest", Aerodynamic::EMERG) === "loggingTest1");
		$ad->log("loggingTest", Aerodynamic::EMERG);

		// since this is static we need to set it back
		$ad->defaultLogHandler = null;

	}

}
?>