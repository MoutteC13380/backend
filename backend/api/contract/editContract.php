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

$contract->idCustomer = $decodedData->idCustomer;
$contract->idPartner = $decodedData->idPartner;
$contract->idAgency = $decodedData->idAgency;
$contract->idBooking = $decodedData->idBooking;
$contract->idCar = $decodedData->idCar;
if (!empty($decodedData->idBackAddress)) {
    $contract->idBackAddress = $decodedData->idBackAddress;
    $contract->idTeammateBack = $decodedData->idTeammateBack;
} else { 
    $contract->idBackAddress = "";
    $contract->idTeammateBack = ""; 
}
if (!empty($decodedData->idForthAddress)) {
    $contract->idForthAddress = $decodedData->idForthAddress;
    $contract->idTeammateForth = $decodedData->idTeammateForth;
} else { 
    $contract->idForthAddress = "";
    $contract->idTeammateForth = "";
}

//On vérifie si le contrat existe déjà
if (!empty($decodedData->idContract)) {
    $contract->idContract = $decodedData->idContract;
    $thisContract = $contract->searchContract($contract);
} else {
    $thisContract = $contract->searchContractByBooking($contract);
}

if (!empty($thisContract['idContract'])) {
    $contract->idContract = $thisContract['idContract']);
    $result = $contract->updateContract($contract);
} else {
    $result = $contract->createContract($contract);
    $contract->way = $decodedData->way;
    //TODO > ajouter l'upload du fichier et changer le nom de urlContract//
    $contract->urlContract = $decodedData->urlInventory;
    //$contract->urlContract = $contract->idContract."_".$contract->way."_".$decodedData->urlInventory;
    $contract->sendInventory($contract);
}

if ($result) {
    echo json_encode([ "message" => "Le contrat a été édité !" ]);
} else {     
    echo json_encode([ "message" => "Le contrat n'a pas pu être édité..." ]);
}