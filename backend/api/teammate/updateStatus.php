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

$teammate->idTeammate = $decodedData->idTeammate;
$teammate->statusTeammate = $decodedData->statusTeammate;
$result = $teammate->updateTeammateStatus($teammate);

if ($result) {
    echo json_encode(["message" => "Le status du Teammate a été modifié !"]);
} else {
    echo json_encode(["message" => "Le status du Teammate n'a pas été modifié..."]);
}
