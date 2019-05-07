<?php

require_once __DIR__ . '/conf/app.conf.php';
require_once __DIR__ . '/conf/database_access.conf.php';

spl_autoload_register(function ($class_name) {
	/** @noinspection PhpIncludeInspection */
	require_once __DIR__ . '/class/' . $class_name . '.class.php';
});

require_once __DIR__.'/lib/functions_core.lib.php';
require_once __DIR__.'/lib/functions_misc.lib.php';


/** @var array $args POST data. */
$action = 'action' . ucfirst($args['action']);
$args = $args['data'] ?? [];

try{
	sendResponse($action($args));
}catch(Exception $e){
	echo json_encode(['success' => false,
					  'debug' => Utils::jTraceEx($e)]);
	die();
}

/**
 * @param array $args
 *
 * @return array
 * @throws Exception
 */
function actionGetCard(array $args) {
	$name = $args['name'];

	$card = Card::getCard($name);

	return $card->toArray();
}

/**
 * @param array $args
 *
 * @return array
 * @throws Exception
 */
function actionGetCardsList(array $args = []) {
	$cards = Card::getAll();

	$list = [];
	foreach($cards as $k => $card){
		$list[] = $card->toArray();
	}

	return [
		'success' => true,
		'data' => $list
	];
}

/**
 * @param array $args
 *
 * @return array
 * @throws DBException
 * @throws Exception
 */
function actionCardsInfo(array $args = []){
	$cards = Card::getAll([['column' => 'multiverse_id', 'sign' => ' IS', 'value' => ' NULL']]);

	$list = [];
	foreach($cards as $k => $card){
		$card->searchCardInfo();
	}
	$cards = Card::getAll();


	$list = [];
	foreach($cards as $k => $card){
		$list[] = $card->toArray();
	}

	return [
		'success' => true,
		'data' => $list
	];
}


