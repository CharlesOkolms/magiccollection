<?php

class Card
{
	private $id;
	private $multiverseId;
	private $scryfallId;
	private $nameEng;
	private $names = ['fra' => '', 'eng' => ''];
	private $set;
	private $setName;
	private $rarity;
	private $cost;
	private $colors;
	private $imgUrl;
	private $price;
	private $lastUpdated;


	function __construct($obj) {

	}

	/**
	 * @param $name
	 *
	 * @return Card|null
	 * @throws AppException
	 */
	public static function getCard($name): ?Card {
		$data = DB::get()->query('SELECT * FROM card WHERE name_eng = :name', ['name' => $name]);
		if (count($data) === 1) {
			$card = new self($data[0]);
		}
		return $card;
	}

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
	public function setMultiverseId(string $multiverseId): void {
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
		$this->nameEng = $nameEng;
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
	 * @return array
	 */
	public function getColors(): ?array {
		return $this->colors;
	}

	/**
	 * @param array $colors
	 */
	public function setColors(array $colors): void {
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
	 * @param float $price
	 */
	public function setPrice(float $price): void {
		$this->price = $price;
	}

	/**
	 * @return string
	 */
	public function getLastUpdated(): ?string {
		return $this->lastUpdated;
	}

	/**
	 * @param string $lastUpdated
	 */
	public function setLastUpdated(string $lastUpdated): void {
		$this->lastUpdated = $lastUpdated;
	}

}
