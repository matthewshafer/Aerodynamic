<?php
namespace Matthewshafer\Aerodynamic;

class Aerodynamic
{
		/**
		 * unix log levels
		 */
		const EMERG = 1;
		const ALERT = 2;
		const CRIT = 4;
		const ERR = 8;
		const WARNING = 16;
		const NOTICE = 32;
		const INFO = 64;
		const DEBUG = 128;

		private static $defaultLogHandler = null;

		private static $logHandlers = array();

		private static $machineName = null;

		public static function registerHandler($handler, $intFlags)
		{
			// possibly throw exception if handler is not what we expect
			if(!self::isClosure($handler))
			{
				throw new \Exception('Handler is not a Closure');
			}

			// 0 sets the defaultLogHandler
			if($intFlags === 0)
			{
				self::$defaultLogHandler = $handler;
			}
			// this is the condition where we are not the default handler, we are handling other errors
			else
			{
				$flag = 1;
				
				// setting up the handler with the errors that can trigger it
				for($i = 1; $i <= 8; $i++)
				{
					// bitwise operations to see if the flag for the handler on a message is set
					if($intFlags & $flag)
					{
						if(!isset(self::$logHandlers[$flag]))
						{
							self::$logHandlers[$flag] = array(0 => $handler);
						}
						// we support multiple fallback handlers
						else
						{
							array_push(self::$logHandlers[$flag], $handler);
						}
					}

					$flag = $flag * 2;
				}
			}
		}

		private static function isClosure($handler)
		{
			$return = false;
			// without the \ it looks in the wrong namespace
			if($handler instanceof \Closure)
			{
				$return = true;
			}

			return $return;
		}

		public static function log($message, $level = 8)
		{
			$logged = false;
			$loggedLoopCt = 0;
			$returnVal = null;
			// setting up the machine name to log if it does not already exist
			if(self::$machineName === null)
			{
				if(isset($_SERVER['SERVER_ADDR']))
				{
					self::$machineName = $_SERVER['SERVER_ADDR'];
				}
				else if(function_exists('gethostname'))
				{
					self::$machineName = gethostname();
				}
				else
				{
					self:$machineName = "localhost";
				}
			}

			// this is where we figure out what handler to use
			if(isset(self::$logHandlers[$level]))
			{
				// loop allows us to use another handler if that one fails
				while(!$logged)
				{
					// dont need to check for a closure as they are already tested when they are added
					if(isset(self::$logHandlers[$level][$loggedLoopCt]))
					{
						$tmp = self::$logHandlers[$level][$loggedLoopCt];
						$returnVal = $tmp(gmdate('Y-m-d H:i:s'), $level, self::$machineName, $message, $logged);
					}

					$loggedLoopCt++;
				}
			}

			// this is if we have never gone through the loop we log it to the default handler
			if($logged === false && self::isClosure(self::$defaultLogHandler))
			{
				$handler = self::$defaultLogHandler;
				$returnVal = $handler(gmdate('Y-m-d H:i:s'), $level, self::$machineName, $message, $logged);
			}

			return $returnVal;
		}

}
?>