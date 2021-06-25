<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With");

include_once "../../config/Database.php";
include_once "../../models/Contract.php";

$db = new Database();
$conn = $db->connect();
$contract = new Contract($conn);

$decodedData = json_decode(file_get_contents("php://input"));

$contract->idContract = $decodedData->idContract;
$contract->idTeammateBack = $decodedData->idTeammateBack;
$result = $contract->addTeammateBack($contract);

if ($result) {
    echo json_encode([ "message" => "Le contrat a été édité !" ]);
} else {     
    echo json_encode([ "message" => "Le contrat n'a pas pu être édité..." ]);
}