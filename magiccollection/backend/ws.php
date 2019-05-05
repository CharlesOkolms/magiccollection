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
$args = $args['data'];

try{
	sendResponse($action($args));
}catch(Exception $e){
	var_dump($e);
//	echo json_encode(['success' => false,
//					  'debug' => Utils::jTraceEx($e)]);
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



