<?php
require_once('../lib/Aerodynamic.php');

class HandlerRegistrationTest extends UnitTest
{
	public function setUpTest()
	{
		
	}

	public function tearDownTest()
	{
		
	}

	public function testDefaultRegistration()
	{
		$refObj = new ReflectClass('Aerodynamic', null);
		$ad = $refObj->getReflection();
		$ad->registerHandler(function(){
			return "test";
		}, 0);

		$out = $ad->defaultLogHandler;

		assert($out() === "test");

		// since this is static we need to set it back
		$ad->defaultLogHandler = null;

	}


	public function testBitmaskRegistration1()
	{
		$refObj = new ReflectClass('Aerodynamic', null);
		$ad = $refObj->getReflection();
		$ad->registerHandler(function(){
			return "test";
		}, 7);

		//var_dump($ad->logHandlers);

		assert(isset($ad->logHandlers[1]) === true);
		assert(isset($ad->logHandlers[2]) === true);
		assert(isset($ad->logHandlers[4]) === true);
		assert(isset($ad->logHandlers[8]) === false);
		assert(isset($ad->logHandlers[16]) === false);
		assert(isset($ad->logHandlers[32]) === false);
		assert(isset($ad->logHandlers[64]) === false);
		assert(isset($ad->logHandlers[128]) === false);

		// setting it back so our later tests pass
		$ad->logHandlers = array();

	}

	public function testBitmaskRegistration2()
	{
		$refObj = new ReflectClass('Aerodynamic', null);
		$ad = $refObj->getReflection();
		$ad->registerHandler(function(){
			return "test";
		}, 129);

		//`var_dump($ad->logHandlers);

		assert(isset($ad->logHandlers[1]) === true);
		assert(isset($ad->logHandlers[2]) === false);
		assert(isset($ad->logHandlers[4]) === false);
		assert(isset($ad->logHandlers[8]) === false);
		assert(isset($ad->logHandlers[16]) === false);
		assert(isset($ad->logHandlers[32]) === false);
		assert(isset($ad->logHandlers[64]) === false);
		assert(isset($ad->logHandlers[128]) === true);

		$ad->logHandlers = array();

	}


	public function testNotClosure()
	{
		$caught = false;
		$refObj = new ReflectClass('Aerodynamic', null);
		$ad = $refObj->getReflection();
		try
		{
			$ad->registerHandler("test", 129);
		}
		catch(Exception $e)
		{
			$caught = true;	
		}

		assert($caught === true);

		$ad->logHandlers = array();

	}
}
?>