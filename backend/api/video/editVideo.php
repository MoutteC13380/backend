<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With");

include_once "../../config/Database.php";
include_once "../../models/Video.php";

$db = new Database();
$conn = $db->connect();
$video = new Video($conn);

$decodedData = json_decode(file_get_contents("php://input"));
$video->idContract = $decodedData->idContract;
$video->videoType = $decodedData->video_type;

$uploadDirectory = 'uploadedFiles/videos/';
$file = $_FILES['userfile']['name'];
$tempName = $_FILES['userfile']['tmp_name'];
$fileError = $_FILES['userfile']['error'];

if ($fileError == UPLOAD_ERR_OK) {   
    $extension = strtolower(pathinfo($file,PATHINFO_EXTENSION));
    $saveName = $video->idContract.'-'.$video->video_type.'-'.uniqid().'.'.$extension;
    move_uploaded_file($tempName, '../../'.$uploadDirectory.$saveName);
} else {
    echo json_encode('Erreur avec le fichier vidéo : '.$fileError);
}
$video->urlCard = $uploadDirectory.$saveName;

$result = $video->createVideo($video);

if ($result) {
    echo json_encode([ "message" => "La vidéo a été créée !" ]);
} else {     
    echo json_encode([ "message" => "La vidéo n'a pas pu être créée..." ]);
}