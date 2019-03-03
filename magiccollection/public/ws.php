<?php

if(empty($_POST['action'])){
	die();
}else {
	$args = $_POST;
	require_once(__DIR__.'/../backend/ws.php');
}
