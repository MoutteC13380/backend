<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
// header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With");

include_once "../../config/Database.php";
include_once "../../models/Car.php";

$db = new Database();
$conn = $db->connect();
$car = new Car($conn);

$decodedData = json_decode(file_get_contents("php://input"));

// $car->idCustomer = $decodedData->idCustomer;
// $car->licensePlateCar = $decodedData->licensePlateCar;
// $car->brandCar = $decodedData->brandCar;
// $car->modelCar = $decodedData->modelCar;
// $car->dateOfCirculationCar = $decodedData->dateOfCirculationCar;
// $car->motorizationCar = $decodedData->motorizationCar;
// $car->versionCar = $decodedData->versionCar;
// $car->colorCar = $decodedData->colorCar;

// if(!empty($decodedData->idCar)) {
//     $car->idCar = $decodedData->idCar;
//     $result = $car->updateCar($car);
// } else {
//     $car->idCar = $car->createCar($car);
//     echo json_encode($car->idCar);
//     if ($car->idCar != NULL) {
//         $result = true;
//     }


echo json_encode([
	'$file' => $_FILES['userfile']['name'],
	'$tempName' => $_FILES['userfile']['tmp_name'],
	'$type' => $_FILES['userfile']['type'],
	'$fileError' => $_FILES['userfile']['error'],
	'$size' => $_FILES['userfile']['size']
]);

// echo json_encode('$_FILES = '.$_FILES);

// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
// 	if (isset($_FILES['file']['name'])) {
// 		if (0 < $_FILES['file']['error']) {
// 			echo 'Error during file upload ' . $_FILES['file']['error'];
// 		} else {
// 			$upload_path = 'uploadedFiles/grayCards/';
// 			if (file_exists($upload_path . $_FILES['file']['name'])) {
// 				echo 'File already exists => ' . $upload_path . $_FILES['file']['name'];
// 			} else {
// 				if (!file_exists($upload_path)) {
// 					mkdir($upload_path, 0777, true);
// 				}
// 				move_uploaded_file($_FILES['file']['tmp_name'], $upload_path . $_FILES['file']['name']);
// 				echo 'File successfully uploaded => "' . $upload_path . $_FILES['file']['name'];
// 			}
// 		}
// 	} else {
// 		echo json_encode('Please choose a file');
// 	}
// 	echo nl2br("\n");
// }

// else {
// 	echo json_encode('Rien dans $_FILES');
// }

// $rawPlate = htmlspecialchars(strip_tags($decodedData->licensePlateCar));
//On retire les espaces et tiret éventuels de la plaque d'immatriculation
// $carPlate = strtoupper(str_replace(["-", " "], "", $rawPlate));

// $uploadDirectory = '../../uploadedFiles/grayCards/';
// $extensions = ['jpg', 'jpeg', 'png', 'gif'];
// $file = $_FILES['file']['name'];
// $tempName = $_FILES['file']['tmp_name'];
// $fileError = $_FILES['file']['error'];

// echo json_encode([$file]);

// if ($fileError == UPLOAD_ERR_OK) {   
//     $extension = strtolower(pathinfo($file,PATHINFO_EXTENSION));
//     if (in_array($extension, $extensions)) {
// 		$saveName = htmlspecialchars(strip_tags($car->idCar)).'_'.$carPlate.'-'.uniqid().'.'.$extension;
// 		move_uploaded_file($tempName, $uploadDirectory.$saveName);
// 		$car->urlGrayCard = $saveName;
//         $car->addGrayCardToCar($car);
//         $result = true;
//     } else {
//         echo json_encode('Le format de l\'image '. $file .' n\'est pas bon');
//     }
// } else {
//     echo json_encode('Erreur avec le fichier image : '.$fileError);
// }

// if ($result) {
//     echo json_encode([ "message" => "Le véhicule a été édité !" ]);
// }  else { 
//     echo json_encode([ "message" => "Le véhicule n'a pas pu être édité..." ]);
// }