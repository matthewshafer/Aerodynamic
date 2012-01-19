<?php
require_once('Teency/Teency/Teency.php');

class AllTests extends TestSuite
{
	public function tests()
	{
		require_once('HandlerRegistrationTest.php');
		$this->load('HandlerRegistrationTest');

		require_once('BasicLoggingTest.php');
		$this->load('BasicLoggingTest');

		require_once('FileLoggerTest.php');
		$this->load('FileLoggerTest');
	}
}

$run = new AllTests();
$run->tests();
?>