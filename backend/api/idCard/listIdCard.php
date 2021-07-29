<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

include_once "../../config/Database.php";
include_once "../../models/IdCard.php";

$db = new Database();
$conn = $db->connect();
$idCard = new idCard($conn);

if (isset($_GET['idContract'])) {
    $idCard->idContract = $_GET['idContract'];
    $result = $idCard->searchIdCardByContract($idCard);
} else {
    $idCards = $idCard->listIdCards();
    $counter = $idCards->rowCount();
    if ($counter > 0) {
        $idCards_array = array();
        while ($row = $idCards->fetch()) {
            extract($row);
            $card_item = [
                 "idCard" => $idCard
                 "idContract" => $idContract,
                 "urlCard" => $urlCard,
            ];
            array_push($idCards_array, $card_item);
        }
        $result = $idCards_array;
    }
}

if (isset($result) && !empty($result)) {
    echo json_encode($result);
} else { 
    http_response_code(404); 
}