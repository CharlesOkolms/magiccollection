<?php

/**
 * Class AppException
 */
class AppException extends Exception
{

	public function __construct($message, $code = 10, Exception $previous = null) {
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
