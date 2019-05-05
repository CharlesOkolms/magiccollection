<?php


/**
 * @version 0.1
 */
final class Scryfall
{


	/**
	 * @param string      $name
	 * @param bool        $isExact
	 * @param null|string $set
	 *
	 * @return array
	 * @throws Exception
	 */
	public static function getCard(string $name, bool $isExact = true, ?string $set = null): array {
		$precision = ($isExact) ? 'exact' : 'fuzzy';

		$parameters = [
			$precision => $name
		];

		if ($set !== null) {
			$parameters['set'] = strval($set);
		}
		$info = self::apiCall($parameters);

		return $info;
	}


	private const SCRYFALL_CARD_EXACT_SEARCH = 1;
	private const SCRYFALL_CARD_FUZZY_SEARCH = 2;

	/**
	 * @param     $data
	 * @param int $mode
	 *
	 * @return array
	 * @throws AppException
	 */
	private static function apiCall(array $data, int $mode = Scryfall::SCRYFALL_CARD_EXACT_SEARCH): array {

		switch ($mode) {
			case Scryfall::SCRYFALL_CARD_EXACT_SEARCH :
				$urlAppend = 'cards/named?' . http_build_query($data, null, '&', PHP_QUERY_RFC3986);
				break;
			default:
				throw new AppException('bad apiCall mode', 1);
		}


		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://api.scryfall.com/' . $urlAppend); // URL to post
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
		$result = curl_exec($ch); // runs the post
		curl_close($ch);

		return json_decode($result, true);
	}

}
