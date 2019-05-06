<?php

/**
 * Class AppException
 */
class AppException extends Exception
{

	const LOGIC_ERROR = 11;
	const UNKNOWN = 10;

	public function __construct($message, $code = self::UNKNOWN, Exception $previous = null) {
		parent::__construct($message, $code, $previous);
	}

	/**
	 * Magic Method toString().
	 *
	 * @return string
	 */
	public function __toString() {
		return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	}

	/**
	 * @uses Utils
	 * @return string
	 */
	public function getStackTrace(){
		return Utils::jTraceEx($this);
	}
}
