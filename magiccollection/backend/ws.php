<?php

require_once __DIR__.'/conf/database_access.conf.php';

spl_autoload_register(function ($class_name) {
	/** @noinspection PhpIncludeInspection */
	require_once __DIR__ . '/class/' . $class_name . '.class.php';
});

foreach ( glob(__DIR__."lib/*.php") as $filename ) {
	/** @noinspection PhpIncludeInspection */
	require_once $filename;
}


/** @var array $args POST data. */
$action = 'action' . ucfirst($args['action']);

sendResponse($action($args));

/**
 * @param array $args
 */
function actionGetCard($args) {

	$name = $args['card']['name'];

	$card = Card::getCard($name);

}



