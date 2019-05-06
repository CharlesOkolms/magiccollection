<?php

class Card implements JsonSerializable
{
	protected $id;
	protected $multiverseId;
	protected $scryfallId;
	protected $nameEng;
	protected $names = ['fra' => '', 'eng' => ''];
	protected $set;
	protected $setName;
	protected $rarity;
	protected $cost;
	protected $colors;
	protected $imgUrl;
	protected $price;
	protected $lastUpdate;
	protected $quantity;


	/**
	 * Card constructor.
	 *
	 * @param array $obj
	 */
	function __construct(array $obj) {
		$this->set($obj);
	}

	/**
	 * @param string      $name
	 * @param null|string $set
	 *
	 * @return Card|null
	 * @throws DBException
	 * @throws Exception
	 */
	public static function getCard(string $name, ?string $set = null): ?Card {
		$set  = $set ?? '%';
		$data = DB::get()->query('SELECT * FROM card WHERE name_eng = :name AND `set` LIKE :set', ['name' => $name, 'set' => $set]);
		$card = null;
		if (count($data) === 1) {
			$card = new self($data[0]);
		}
		else if (count($data) === 0) {
			$info = Scryfall::getCard($name);

			$card = new Card(['colors'       => $info['colors'],
							  'price'        => $info['prices']['eur'],
							  'scryfallId'   => $info['id'],
							  'multiverseId' => implode(',', $info['multiverse_ids']),
							  'imgUrl'       => $info['image_uris']['normal'],
							  'cost'         => $info['mana_cost'],
							  'nameEng'      => $info['name'],
							  'rarity'       => $info['rarity'],
							  'set'          => $info['set'],
							  'setName'      => $info['set_name']]);

		}
//		else if (count($data) > 1) {
//			// todo
//		}


		return $card;
	}


	/**
	 * Get the list of all cards stored in database.
	 *
	 * @return Card[]
	 * @throws DBException
	 */
	public static function getAll(): array {
		$sql   = 'SELECT * FROM card';
		$list  = DB::select($sql);
		$cards = [];

		foreach ($list as $k => $cardRow) {
			$cards[] = new self($cardRow);
		}
		return $cards;
	}
	/**
	 * Set all properties of the Card object with the ones defined in the array parameter.
	 *
	 * @param array $data
	 */
	private function set(array $data) {
		foreach ($data as $property => $value) {
			switch ($property) {
				default:
					$property = str_replace('_', '', ucwords($property, '_'));
					break;
			}
			$this->{'set' . $property}($value);
		}
	}

	#region setters getters

	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId(int $id): void {
		$this->id = $id;
	}

	/**
	 * @return int|null
	 */
	public function getMultiverseId(): ?int {
		return $this->multiverseId;
	}

	/**
	 * @param string $multiverseId
	 */
	public function setMultiverseId(?string $multiverseId): void {
		$this->multiverseId = $multiverseId;
	}

	/**
	 * @return mixed
	 */
	public function getScryfallId(): ?string {
		return $this->scryfallId;
	}

	/**
	 * @param mixed $scryfallId
	 */
	public function setScryfallId($scryfallId): void {
		$this->scryfallId = $scryfallId;
	}

	/**
	 * @return string
	 */
	public function getNameEng(): ?string {
		return $this->nameEng;
	}

	/**
	 * @param string $nameEng
	 */
	public function setNameEng(string $nameEng): void {
		$this->nameEng      = $nameEng;
		$this->names['eng'] = strval($nameEng);
	}

	/**
	 * @param string $name
	 */
	public function setNameFra(string $name): void {
		$this->names['fra'] = strval($name);
	}

	/**
	 * @return string
	 */
	public function getNameFra(): ?string {
		return $this->names['fra'];
	}


	/**
	 * @return array
	 */
	public function getNames(): array {    // methode magique __get / __set pour le cas de Name et qui ecrirait dans name[] ?
		return $this->names;
	}

	/**
	 * @param array $names
	 */
	public function setNames(array $names): void {
		$this->names = $names;
	}

	/**
	 * @return string
	 */
	public function getSet(): ?string {
		return $this->set;
	}

	/**
	 * @param string $set
	 */
	public function setSet(string $set): void {
		$this->set = $set;
	}

	/**
	 * @return string
	 */
	public function getSetName(): ?string {
		return $this->setName;
	}

	/**
	 * @param string $setName
	 */
	public function setSetName(string $setName): void {
		$this->setName = $setName;
	}

	/**
	 * @return string
	 */
	public function getRarity(): ?string {
		return $this->rarity;
	}

	/**
	 * @param string $rarity
	 */
	public function setRarity(string $rarity): void {
		$this->rarity = $rarity;
	}

	/**
	 * @return string
	 */
	public function getCost(): ?string {
		return $this->cost;
	}

	/**
	 * @param string $cost
	 */
	public function setCost(string $cost): void {
		$this->cost = $cost;
	}

	/**
	 * @param bool $asString
	 *
	 * @return array|string
	 */
	public function getColors(bool $asString = false) {
		return ($asString) ? implode(',', $this->colors) : $this->colors;
	}

	/**
	 * @param array|string $colors Array of letters representing colors or comma separated letters in string.
	 *
	 * @throws AppException
	 */
	public function setColors($colors): void {
		if (is_string($colors)) {
			$colors = explode(',', $colors);
		}
		foreach ($colors as $k => $letter) {
			$colors[$k] = strval($letter);
			if (in_array($letter, ['W', 'U', 'B', 'R', 'G'])) {
				throw new AppException('Incorrect letter ' . $letter . ' for colors of card.');
			}
		}
		$this->colors = $colors;
	}

	/**
	 * @return string
	 */
	public function getImgUrl(): ?string {
		return $this->imgUrl;
	}

	/**
	 * @param string $imgUrl
	 */
	public function setImgUrl(string $imgUrl): void {
		$this->imgUrl = $imgUrl;
	}

	/**
	 * @return float
	 */
	public function getPrice(): ?float {
		return $this->price;
	}

	/**
	 * @param float|string $price
	 *
	 * @throws AppException
	 */
	public function setPrice($price): void {
		$price = floatval($price);
		if ($price === 0) {
			$price = null;
		}
		else if ($price < 0) {
			throw new AppException('Inconsistent price (' . $price . ') of card', AppException::LOGIC_ERROR);
		}
		$this->price = $price;
	}

	/**
	 * @return string
	 */
	public function getLastUpdate(): ?string {
		return $this->lastUpdate;
	}

	/**
	 * @param string $lastUpdate
	 */
	public function setLastUpdate(string $lastUpdate): void {
		if($lastUpdate === '0000-00-00 00:00:00'){
			$lastUpdate = null;
		}
		$this->lastUpdate = $lastUpdate;
	}

	/**
	 * @return mixed
	 */
	public function getQuantity() {
		return $this->quantity;
	}

	/**
	 * @param mixed $quantity
	 */
	public function setQuantity($quantity): void {
		$qt             = intval($quantity);
		$this->quantity = $qt;
	}


	#endregion get/set

	/**
	 * Specify data which should be serialized to JSON
	 *
	 * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		return get_object_vars($this);
	}

	/**
	 * @return array
	 */
	public function toArray() {
		return get_object_vars($this);
	}


}
