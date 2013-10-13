<?php
      
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

	require_once("FoursquareAPI.class.php");
	$name = array_key_exists("name",$_GET) ? $_GET['name'] : "Chris Hersh";

	
	
	$client_key = "HWX4LG3G1CHBYGCQMI3J0P2G1EMTD4EWD2JVI55I1GNKKH0R";
	$client_secret = "XDUVOX0BXVVF4WSVTBCS2QDE5WHFXNGII3JJISHVOZ0AJOVW";
	$redirect_uri = "http://localhost/test.php";
	$uri = "https://foursquare.com/oauth2/authenticate?client_id={$client_key}&response_type=code&redirect_uri={$redirect_uri}";
	
	$foursquare = new FoursquareAPI($client_key,$client_secret);
	
	if(array_key_exists("code",$_GET)){
		$token = $foursquare->GetToken($_GET['code'],$redirect_uri);
	}
	

	if(!isset($token)){ 
		header('Location: '.$uri);
		echo "Hello";
	}
	$foursquare->SetAccessToken($token);

	$params = array("name"=>$name);

	$response = $foursquare->GetPrivate("users/self/checkins");
	$users = json_decode($response);	
	print "<pre>";
	print_r($users);
	print "</pre>";
?>
