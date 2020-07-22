<?php
include "db.php";

if(isset($_GET["token"])){
	$token = $_GET["token"];
	$sql = "SELECT username, apitoken FROM users WHERE apitoken='".$token."'";

	$query = $pdo->query($sql, PDO::FETCH_ASSOC);
	$response = array();

	foreach ($query as $row) {
		$user=$row["USERNAME"];
		$response["api_user"][] = $row;
	}

	if(isset($_GET["permit"])){
		$response["api_response"] = permit($user, $_GET["permit"], $pdo);
	}else if(isset($_GET["getusers"])){
		$response["api_response"] = getusers($pdo);
	}else if(isset($_GET["getuser"])){
		$response["api_response"] = getuser($_GET["getuser"], $pdo);
	}

	header('CONTENT-TYPE: application/json');
	echo json_encode($response, JSON_PRETTY_PRINT);
}


function permit($user, $topermit, $pdo){
	$status = true;
	$sql = "SELECT * FROM secrets WHERE owner='".$user."'";
	
	foreach($pdo->query($sql) as $row){
		if($row["PERMITTED"] == ''){
			$updatesql = "UPDATE secrets SET permitted='".$topermit."' WHERE id='".$row["ID"]."'";;
		}else{
			$updatesql = "UPDATE secrets SET permitted=CONCAT(permitted, ';', '".$topermit."') WHERE id='".$row["ID"]."'";
		}

		if(!$pdo->query($updatesql)) $status = false;
	}

	if(!$status){
		return array('status' => 'ERROR');
	}else{
		return array('status' => 'OK');
	}
}

function getusers($pdo){
	$sql = "SELECT username, role, apitoken FROM users";
	$query = $pdo->query($sql, PDO::FETCH_ASSOC);

	$rows = array();
	foreach ($query as $row) {
		$rows[] = $row;
	}

	return $rows;
}

function getuser($user, $pdo){
	$sql = "SELECT username, role, apitoken FROM users WHERE username='".$user."'";
	$query = $pdo->query($sql, PDO::FETCH_ASSOC);

	$rows = array();
	foreach ($query as $row) {
		$rows[] = $row;
	}

	return $rows;
}
?>