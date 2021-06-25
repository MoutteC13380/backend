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
$contract->way = $decodedData->way;
//TODO > ajouter l'upload du fichier et changer le nom de urlContract//
$contract->urlContract = $decodedData->urlInventory;
//$contract->urlContract = $contract->idContract."_".$contract->way."_".$decodedData->urlInventory;
$contract->sendInventory($contract);

if ($result) {
    echo json_encode([ "message" => "L'inventory a été ajouté !" ]);
} else {     
    echo json_encode([ "message" => "L'inventory n'a pas pu être ajouté..." ]);
}