<?php

class Utils {

	/**
	 * jTraceEx() - provide a Java style exception trace
	 *
	 * @link https://www.php.net/manual/fr/exception.gettraceasstring.php#114980
	 * @author ernest at vogelsinger dot at
	 *
	 * @param Exception $e
	 * @param array 	$seen - array passed to recursive calls to accumulate trace lines already seen. Leave as NULL when you manually call this function.
	 *
	 * @return string
	 */
	public static function jTraceEx($e, $seen = null) {
		if(PROD){
			return '';
		}
		$starter = $seen ? 'Caused by: ' : '';
		$result  = [];
		if (!$seen) {
			$seen = [];
		}
		$trace    = $e->getTrace();
		$prev     = $e->getPrevious();
		$result[] = sprintf('%s%s: %s', $starter, get_class($e), $e->getMessage());
		$file     = $e->getFile();
		$line     = $e->getLine();

		while (true) {
			$current = "$file:$line";
			if (is_array($seen) && in_array($current, $seen)) {
				$result[] = sprintf(' ... %d more', count($trace) + 1);
				break;
			}
			$result[] = sprintf(' at %s%s%s(%s%s%s)',
								count($trace) && array_key_exists('class', $trace[0]) ? str_replace('\\', '.', $trace[0]['class']) : '',
								count($trace) && array_key_exists('class', $trace[0]) && array_key_exists('function', $trace[0]) ? '.' : '',
								count($trace) && array_key_exists('function', $trace[0]) ? str_replace('\\', '.', $trace[0]['function']) : '(main)',
								$line === null ? $file : basename($file),
								$line === null ? '' : ':',
								$line === null ? '' : $line);
			if (is_array($seen)) {
				$seen[] = "$file:$line";
			}
			if (!count($trace)) {
				break;
			}
			$file = array_key_exists('file', $trace[0]) ? $trace[0]['file'] : 'Unknown Source';
			$line = array_key_exists('file', $trace[0]) && array_key_exists('line', $trace[0]) && $trace[0]['line'] ? $trace[0]['line'] : null;
			array_shift($trace);
		}

		$result = join("\n", $result);
		if ($prev) {
			$result .= "\n" . self::jTraceEx($prev, $seen);
		}

		return $result;
	}


	/**
	 * @param string $property Property name
	 * @param string $mode 'set' or 'get'.
	 *
	 * @return mixed
	 */
	public static function stringToGetSet(string $property, string $mode){
		return str_replace('_', '', ucwords($property, '_'));
	}

}
