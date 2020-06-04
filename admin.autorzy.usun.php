<?php

ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);
session_start();

require_once 'vendor/autoload.php';

use Ibd\Autorzy;

if(isset($_POST)) {
	$autorzy = new Autorzy();
	if(!($autorzy->czyMaKsiazki($_GET['id']))){
		if($autorzy->usun($_GET['id']))
			echo 'ok';
	}
	else {
		echo 'Nie można usunąć, autor posiada powiązane ksiązki';
	}
	
}
