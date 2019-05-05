<?php


/** Send the response object in a JSON string with echo and terminates the processus.
 * @param array $a
 */
function sendResponse(array $a){
	echo json_encode($a,true);
	die();
}
