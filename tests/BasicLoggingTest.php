<?php
require_once('../lib/Aerodynamic.php');

class BasicLoggingTest extends UnitTest
{
	public function setUpTest()
	{
		
	}

	public function tearDownTest()
	{
		
	}

	public function testDefaultLogging()
	{
		$refObj = new ReflectClass('Aerodynamic', null);
		$ad = $refObj->getReflection();
		$ad->registerHandler(function($date, $level, $machine, $message, &$logged){
			$logged = true;
			return $message . $level;
		}, 0);

		//var_dump($ad->log("loggingTest", Aerodynamic::EMERG));
		assert($ad->log("loggingTest", Aerodynamic::EMERG) === "loggingTest1");

		// since this is static we need to set it back
		$ad->defaultLogHandler = null;

	}

	public function testDefaultLoggingTwoHandlersFirst()
	{
		$refObj = new ReflectClass('Aerodynamic', null);
		$ad = $refObj->getReflection();
		$ad->registerHandler(function($date, $level, $machine, $message, &$logged){
			$logged = true;
			return $message . $level;
		}, Aerodynamic::EMERG);

		$ad->registerHandler(function($date, $level, $machine, $message, &$logged){
			$logged = true;
			return $message . $level . $message;
		}, Aerodynamic::EMERG);

		//var_dump($ad->log("loggingTest", Aerodynamic::EMERG));
		assert($ad->log("loggingTest", Aerodynamic::EMERG) === "loggingTest1");

		// since this is static we need to set it back
		$ad->defaultLogHandler = null;
		$ad->logHandlers = array();

	}

	public function testDefaultLoggingTwoHandlersSecond()
	{
		$refObj = new ReflectClass('Aerodynamic', null);
		$ad = $refObj->getReflection();
		$ad->registerHandler(function($date, $level, $machine, $message, &$logged){
			$logged = false;
			return $message . $level;
		}, Aerodynamic::EMERG);

		$ad->registerHandler(function($date, $level, $machine, $message, &$logged){
			$logged = true;
			return $message . $level . $message;
		}, Aerodynamic::EMERG);

		//var_dump($ad->log("loggingTest", Aerodynamic::EMERG));
		assert($ad->log("loggingTest", Aerodynamic::EMERG) === "loggingTest1loggingTest");

		// since this is static we need to set it back
		$ad->defaultLogHandler = null;
		$ad->logHandlers = array();

	}

}
?>