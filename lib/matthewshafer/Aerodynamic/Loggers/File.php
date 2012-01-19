<?php
namespace Matthewshafer\Aerodynamic\Loggers;

class File
{
	public static function init($fileName, $format = "%s - %d - %s - %s\n")
	{
		return function($date, $level, $machine, $message, &$logged) use($fileName, $format){
				
			$file = fopen($fileName, 'a+');

			if($file && flock($file, LOCK_EX | LOCK_NB))
			{
				fwrite($file, sprintf($format, $date, $level, $machine, $message));
				flock($file, LOCK_UN);
				fclose($file);
				$logged = true;
			}
		};
	}
}
?>