<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With");

include_once "../../config/Database.php";
include_once "../../models/Teammate.php";

$db = new Database();
$conn = $db->connect();
$teammate = new Teammate($conn);

$decodedData = json_decode(file_get_contents("php://input"));

$teammate->mixedPassword = $decodedData->oldPasword;
$password = $decodedData->newPassword;

if (isset($decodedData->idTeammate)) {
	$teammate->idTeammate = $decodedData->idTeammate;
	$thisTeammate = $teammate->searchTeammateById($teammate);
} else if (isset($decodedData->usernameTeammate)) {
	$teammate->usernameTeammate = $decodedData->usernameTeammate;
	$thisTeammate = $teammate->searchTeammateByUsername($teammate);	
} 

if (!is_null($thisTeammate)) {
	if (password_verify($password, $thisTeammate['mixedPassword'])) {
		$teammate->idTeammate = $thisTeammate['idTeammate'];
		$teammate->mixedPassword = $password;
		$result = $teammate->passwordUpdate($teammate);
	} else {
		$error = 'Le mot de passe de correspond pas';
	}
} else {
	$error = 'Le compte utilisateur n\'existe pas';
}

if ($result) {
    echo json_encode(["message" => "Le status du Teammate a été modifié !"]);
} else {
    echo json_encode(["error" => $error]);
}
