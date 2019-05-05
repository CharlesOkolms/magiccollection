<?php

/**
 * Singleton class using PDO prepared statements through static methods.
 *
 * @since 0.1
 * @author charlesokolms
 */
class DB
{
	/** @var $_instance DB Instance used by the singleton and containing the PDO object */
	private static $_instance;
	/** @var $_db PDO The PDO object used by the singleton */
	private $_db;

	/**
	 * @var string Datetime format as used in the database (ISO-8601 simplified)
	 */
	const DATETIME_FORMAT = 'Y-m-d H:i:s';

	/** @var string Date format as in the database (same as date in ISO-8601) */
	const DATE_FORMAT = 'Y-m-d';

	/** DataBase instance constructor. */
	private function __construct() {
		try {
			$this->_db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PWD);
		} catch (Exception $e) {
			echo 'DataBase connection error : ' . $e->getMessage() . '<br />';
			echo '#' . $e->getCode();
		}
	}

	/** DataBase destructor. */
	public function __destruct() {
		self::$_instance = null;
	}

	/**
	 * Singleton (une seule et unique instance PDO pour tout le script) donc on doit retrouver l'instance à utiliser
	 *
	 * @return DB : L'instance de la classe DataBase. (on ne peut instancier qu'une seule fois cette classe)
	 */
	public static function get(): DB {
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}


	/**
	 * Use a prepared statement to execute a SELECT query and returns the result fetched as specified, defaults to an
	 * associative array
	 * Example :
	 * $usersList = DataBase::getInstance()->query('SELECT * FROM user WHERE id = :id', [':id'=>12], PDO::FETCH_ASSOC);
	 *
	 * @param string $sql SQL SELECT query string to be executed.
	 * @param array  $values Values of the named or non-named variables in the query.
	 * @param int    $fetchmode PDO fetch mode. Defaults to PDO::FETCH_ASSOC.
	 *
	 * @return array
	 * @author charlesokolms
	 * @since 0.1
	 * @throws AppException
	 */
	public function query(string $sql, ?array $values, ?int $fetchmode = PDO::FETCH_ASSOC): array {
		$values    = $values ?? [];
		$fetchmode = $fetchmode ?? PDO::FETCH_ASSOC;

		$statement = $this->getPDO()->prepare($sql);
		$statement->execute($values);
		$result = $statement->fetchAll($fetchmode);

		if (!is_array($result)) {
			throw new AppException(json_encode($this->getPDO()->errorInfo(), true));
		}
		return $result;
	}

	/**
	 * Permet de préparer et executer une requete SQL de manipulation de données et, en cas d'erreur, d'en récupérer
	 * les informations.
	 *
	 * @param string $sql : Requête SQL (autre que SELECT) à executer, avec des points d'interrogation "?" ou des
	 *                       variables nommées ":nomvariable" si besoin de variable dans la requête.
	 * @param array  $values : Contient les valeurs à attribuer aux inconnues de la requête SQL. En cas d'utilisation
	 *                       des "?", le tableau contient simplement les valeurs sans index particulier.
	 *
	 * @return mixed         : Retourne les informations de l'erreur dans le cas où une erreur survient, le dernier id
	 *                       inséré pour un INSERT ou le nombre de lignes affectées pour un UPDATE ou encore FALSE si
	 *                       la requete n'est ni un INSERT ni un UPDATE.
	 */
	public function action(string $sql, array $values) {
		$sql  = trim($sql);
		$type = strtoupper(substr($sql, 0, 6));

		switch ($type) {
			case 'INSERT':
			case 'UPDATE':
				break;
			default: // si ce n'est ni un update ni un insert on annule l'action et on retourne false
				return false;
				break;
		}

		$pdostatement = $this->getPDO()->prepare($sql);
		$executed     = $pdostatement->execute($values);
		if (!$executed) {
			return $pdostatement->errorInfo(); // si execute renvoie false, on retourne les erreurs MySQL
		}

		switch ($type) {
			case 'INSERT':
				return self::get()->lastInsertId(); // si c'était un INSERT, on retourne le dernier id inséré
				break;
			case 'UPDATE':
				return $pdostatement->rowCount(); // si c'était un UPDATE, on retourne le nombre de lignes affectées
				break;
			default:
				return true;
				break;
		}
	}

	/**
	 * @return bool TRUE en cas de succès, FALSE sinon.
	 */
	public function beginTransaction(): bool {
		return $this->getPDO()->beginTransaction();
	}

	/**
	 * @return bool TRUE en cas de succès, FALSE sinon.
	 */
	public function commit(): bool {
		return $this->getPDO()->commit();
	}

	/**
	 * @return bool TRUE en cas de succès, FALSE sinon.
	 */
	public function rollback(): bool {
		return $this->getPDO()->rollback();
	}

	/**
	 * @return PDO : L'objet de connexion PDO
	 */
	public function getPDO() {
		return $this->_db;
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
