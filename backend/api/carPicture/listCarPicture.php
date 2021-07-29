<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

include_once "../../config/Database.php";
include_once "../../models/carPictures.php";

$db = new Database();
$conn = $db->connect();
$carPicture = new CarPicture($conn);

if (isset($_GET['idContract'])) {
    $carPicture->idContract = $_GET['idContract'];
    $carPictures = $carPicture->listPicturesByContract($carPicture);
} else {
    $carPicture->listPictures();
}
$counter = $carPictures->rowCount();
if ($counter > 0) {
    $pictures_array = array();
    while ($row = $carPictures->fetch()) {
        $picture_item = [
            "idPicture" => $idPicture,
            "urlPicture" => $urlPicture,
            "idContract" => $idContract,
            "datePicture" => $datePicture,
            "location" => $location
        ];
        array_push($pictures_array, $picture_item);
    }
}
$result = $pictures_array;

if (isset($result) && !empty($result)) {
    echo json_encode($result);
} else { 
    http_response_code(404); 
}