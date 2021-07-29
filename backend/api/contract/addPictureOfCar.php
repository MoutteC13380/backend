<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With");

include_once "../../config/Database.php";
include_once "../../models/CarPicture.php";

$db = new Database();
$conn = $db->connect();
$carPicture = new CarPicture($conn);

$decodedData = json_decode(file_get_contents("php://input"));

$carPicture->idContract = $decodedData->idContract;
$carPicture->location = $decodedData->location;

$uploadDirectory = 'uploadedFiles/carPictures/';
$extensions = ['jpg', 'jpeg', 'png', 'gif'];
$file = $_FILES['userfile']['name'];
$tempName = $_FILES['userfile']['tmp_name'];
$fileError = $_FILES['userfile']['error'];

if ($fileError == UPLOAD_ERR_OK) {   
    $extension = strtolower(pathinfo($file,PATHINFO_EXTENSION));
    if (in_array($extension, $extensions)) {
        $saveName = $carPicture->idContract.'-'.uniqid().'.'.$extension;
        move_uploaded_file($tempName, '../../'.$uploadDirectory.$saveName);
    } else {
        echo 'Le format de l\'image '. $file .' n\'est pas bon';
    }
} else {
    echo json_encode('Erreur avec le fichier image : '.$fileError);
}
$carPicture->urlPicture = $uploadDirectory.$saveName;

$result = $idCard->createIdCard($idCard);

if ($result) {
    echo json_encode([ "message" => "La photo a été enregistrée !" ]);
} else {
    echo json_encode([ "message" => "La photo n'a pas pu être enregistrée..." ]);
}