<?php


/** Send the response object in a JSON string with echo and terminates the processus.
 * @param array $resp
 */
function sendResponse(array $resp){
	echo json_encode($resp,true);
	die();
}
