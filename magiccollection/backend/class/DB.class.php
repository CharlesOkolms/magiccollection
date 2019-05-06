<?php

/**
 * Singleton class using PDO prepared statements through static methods.
 *
 * @version 0.3.2
 * @author  charlesokolms
 * @todo Accessibilité statique plutôt que/en plus de méthodique ? ; uniformiser et généraliser retours ; finir traduction ;
 */
class DB
{
	/** @var $_instance DB Instance used by the singleton and containing the PDO object */
	private static $_instance;
	/** @var $_db PDO The PDO object used by the singleton */
	private $_db;


	/** @var string Datetime format as used in the database (ISO-8601 simplified) */
	const DATETIME_FORMAT = 'Y-m-d H:i:s';

	/** @var string Date format as in the database (same as date in ISO-8601) */
	const DATE_FORMAT = 'Y-m-d';

	/** DataBase instance constructor.
	 *
	 * @throws DBException
	 * @throws Exception
	 */
	private function __construct() {
		try {
			$this->_db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD, [
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			]);
			$this->_db->exec('SET CHARACTER SET utf8;');
		} catch (Exception $e) {
			throw new DBException('DataBase connection error : ' . $e->getMessage(), null, $e);
		}
	}

	/** DataBase destructor. */
	public function __destruct() {
		self::$_instance = null;
	}

	/**
	 * Get singleton instance of DB class.
	 *
	 * @return DB The only one instance of DB class.
	 * @throws Exception
	 */
	public static function get(): DB {
		try {
			if (empty(self::$_instance)) {
				self::$_instance = new self();
			}
			return self::$_instance;
		} catch (Exception $e) {
			throw $e;
		}
	}


	/**
	 * Use a prepared statement to execute a SELECT query and returns the result fetched as specified (defaults to an
	 * associative array)
	 * Example :
	 * $usersList = DataBase::getInstance()->query('SELECT * FROM user WHERE id = :id', [':id'=>12], PDO::FETCH_ASSOC);
	 *
	 * @param string $sql SQL SELECT query string to be executed.
	 * @param array  $values Values of the named or non-named variables in the query.
	 * @param int    $fetchmode PDO fetch mode. Defaults to PDO::FETCH_ASSOC.
	 *
	 * @return array
	 * @throws DBException
	 * @author charlesokolms
	 * @since  0.1
	 */
	public function query(string $sql, ?array $values = null, ?int $fetchmode = PDO::FETCH_ASSOC): array {
		$values    = $values ?? [];
		$fetchmode = $fetchmode ?? PDO::FETCH_ASSOC;

		$statement = $this->getPDO()->prepare($sql);
		$statement->execute($values);
		$result = $statement->fetchAll($fetchmode);

		if (!is_array($result)) {
			throw new DBException('SQL Query failed.', $this->getPDO()->errorInfo());
		}
		return $result;
	}

	/**
	 * Prepare and execute a SQL statement manipulating data. Throws DBException on error.
	 *
	 * @param string     $sql Non-select SQL statement to be executed.
	 * @param array|null $values Values of parameters. Parameters can be named or not.
	 *
	 * @return mixed             The last inserted id and the row count.
	 * @throws DBException
	 */
	public function action(string $sql, ?array $values = null) {
		$sql  = trim($sql);
		$type = strtoupper(substr($sql, 0, 6));

		switch ($type) {
			case 'INSERT':
			case 'UPDATE':
				break;
			default:
				break;
		}

		try {
			$pdostatement = $this->getPDO()->prepare($sql);
			$executed     = $pdostatement->execute($values);
			if (!$executed) {
				throw new DBException('SQL Action failed', $pdostatement->errorInfo());
			}

			return [
				'success'      => true,
				'lastInsertId' => self::get()->lastInsertId(),
				'rowCount'     => $pdostatement->rowCount()
			];
		} catch (DBException $dbE) {
			throw $dbE;
		} catch (PDOException $pdoE) {
			throw new DBException('An error has occurred with PDO.', null, $pdoE);
		} catch (Exception $e) {
			throw new DBException('An error has occurred.', null, $e);
		}
	}


	/**
	 * @param string     $sql
	 * @param array|null $values
	 * @param int|null   $fetchmode
	 *
	 * @return array
	 * @throws DBException
	 */
	public static function select(string $sql, ?array $values = null, ?int $fetchmode = PDO::FETCH_ASSOC): array {
		try {
			return self::get()->query($sql, $values, $fetchmode);
		} catch (DBException $e) {
			throw $e;
		} catch (Exception $e) {
			throw new DBException('Error : ' . $e->getMessage(), [], $e);
		}
	}

	/** @return bool TRUE en cas de succès, FALSE sinon. */
	public function beginTransaction(): bool {
		return $this->getPDO()->beginTransaction();
	}

	/** @return bool TRUE en cas de succès, FALSE sinon. */
	public function commit(): bool {
		return $this->getPDO()->commit();
	}

	/** @return bool TRUE en cas de succès, FALSE sinon. */
	public function rollback(): bool {
		return $this->getPDO()->rollback();
	}

	/** @return PDO : L'objet de connexion PDO */
	public function getPDO() {
		return $this->_db;
	}

	/**
	 * @return PDO
	 * @throws Exception
	 */
	public static function pdo(){
		return self::get()->getPDO();
	}


	/**
	 * Retourne le dernier id que l'on a inséré en base de données par le biais de cette connexion.
	 *
	 * @return string : Le dernier id enregistré dans la base de données par cette connexion PDO.
	 */
	public function lastInsertId(): string {
		return $this->getPDO()->lastInsertId();
	}
}


class DBException extends Exception
{

	protected $databaseError;

	/**
	 * DBException constructor.
	 *
	 * @param string    $message short explanation about what happened.
	 * @param array     $errors [optional] what PDO returns in case of error.
	 * @param Throwable $previous [optional] the previous Exception in case you want to get the error stack trace.
	 */
	public function __construct(string $message, ?array $errors = [], Throwable $previous = null) {
		parent::__construct($message, 12, $previous);
		$this->databaseError = $errors ?? [];
	}

	/**
	 * Magic Method toString().
	 *
	 * @return string
	 */
	public function __toString() {
		$errors = print_r($this->databaseError, true);
		return __CLASS__ . ": [{$this->code}]: {$this->message} - {$errors}\n";
	}

	/**
	 * @return string
	 * @uses Utils
	 */
	public function getStackTrace() {
		return Utils::jTraceEx($this);
	}
}
