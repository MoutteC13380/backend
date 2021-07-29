<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With");

include_once "../../config/Database.php";
include_once "../../models/TechnicalControlInvoice.php";

$db = new Database();
$conn = $db->connect();
$technicalControlInvoice = new TechnicalControlInvoice($conn);

$decodedData = json_decode(file_get_contents("php://input"));

$technicalControlInvoice->idPartner = $decodedData->idPartner;
$technicalControlInvoice->monthlyInvoice = $decodedData->monthlyInvoice;
$technicalControlInvoice->priceInvoice = $decodedData->priceInvoice;

$nInvoices = $technicalControlInvoice->listInvoices();
$n = $nInvoices->rowCount()+1;
$ncInvoices = $technicalControlInvoice->listInvoicesByMonth($technicalControlInvoice);
$nc = $ncInvoices->rowCount()+1;
$technicalControlInvoice->invoiceNumber = $date('Ymj')."TC".$n.'-'.$nc;

$uploadDirectory = 'uploadedFiles/technicalControlInvoices/';
$extensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
$file = $_FILES['userfile']['name'];
$tempName = $_FILES['userfile']['tmp_name'];
$fileError = $_FILES['userfile']['error'];

if ($fileError == UPLOAD_ERR_OK) {   
    $extension = strtolower(pathinfo($file,PATHINFO_EXTENSION));
    if (in_array($extension, $extensions)) {
        $saveName = $technicalControlInvoice->invoiceNumber.'-'.uniqid().'.'.$extension;
        move_uploaded_file($tempName, '../../'.$uploadDirectory.$saveName);
    } else {
        echo 'Le format de l\'image '. $file .' n\'est pas bon';
    }
} else {
    echo json_encode('Erreur avec le fichier image : '.$fileError);
}
$technicalControlInvoice->urlInvoice = $uploadDirectory.$saveName;

$result = $technicalControlInvoice->createInvoice($technicalControlInvoice);

if ($result) {
    echo json_encode([ "message" => "La facture a été édité !" ]);
}  else { 
    echo json_encode([ "message" => "La facture n'a pas pu être édité..." ]);
}