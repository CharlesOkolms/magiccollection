<?php


final class Scryfall
{


	/**
	 * @param      $name
	 * @param bool $isExact
	 *
	 * @return Card
	 * @throws AppException
	 */
	public static function getCard(string $name, ?bool $isExact = true) {

		$param = ($isExact) ? 'exact' : 'fuzzy';

		$info = self::apiCall([$param => $name]);

		return new Card([]);

	}


	private static final const SCRYFALL_CARD_EXACT_SEARCH = 1;

	/**
	 * @param     $data
	 * @param int $mode
	 *
	 * @return mixed
	 * @throws AppException
	 */
	private static function apiCall(array $data, int $mode = Scryfall::SCRYFALL_CARD_EXACT_SEARCH) {

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

		return $result;
	}

}
