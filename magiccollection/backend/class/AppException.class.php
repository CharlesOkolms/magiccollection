<?php

/**
 * Class AppException
 */
class AppException extends Exception
{

	public function __construct($message, $code = 0, Exception $previous = null) {
		// traiter le cas d'une erreur BDD avec array ici ou dans DataBase ?

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

}
