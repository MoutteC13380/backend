<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With");

include_once "../../config/Database.php";
include_once "../../models/Agency.php";

$db = new Database();
$conn = $db->connect();
$agency = new Agency($conn);

$decodedData = json_decode(file_get_contents("php://input"));

$agency->idAgency = $decodedData->idAgency;
$agency->statusAgency = $decodedData->statusAgency;
$result = $agency->updateStatusAgency($agency);

if ($result) {
    echo json_encode(["message" => "L'agence a été éditée !"]);
} else {
    echo json_encode(["message" => "L'agence n'a pas pu être éditée..."]);
}