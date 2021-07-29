<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With");

include_once "../../config/Database.php";
include_once "../../models/CustomerInvoices.php";
include_once "../../models/Contract.php";
include_once "../../models/Prices.php";
include_once "../../models/Booking.php";

$db = new Database();
$conn = $db->connect();
$customerInvoice = new CustomerInvoices($conn);
$contract = new Contract($conn);
$price = new Price($conn);
$booking = new Booking($conn);

$decodedData = json_decode(file_get_contents("php://input"));

$customerInvoice->invoiceDate = date('Y-m');
$nInvoices = $customerInvoice->listCustomerInvoices();
$n = $nInvoices->rowCount()+1;
$ncInvoices = $customerInvoice->listInvoicesByDate($customerInvoice);
$nc = $ncInvoices->rowCount()+1;
$customerInvoice->invoiceNumber = $date('Ymj').$n.'-'.$nc;

$contract->idContract = $decodedData->idContract;
$thisContract = $contract->searchContract($contract);
$customerInvoice->invoiceLines = $thisContract['additionnalFees'];
$booking->idBooking = $thisContract['idBooking'];
$thisBooking = searchBookingById($booking);

$prices = $price->listPrices();
$counter = $prices->rowCount();
if ($counter > 0) {
    $prices_array = array();
    while($row = $prices->fetch()) {
        extract($row);
        array_push($prices_array, $typePrice => $amount);
    }
}

for ($i = 0; $i = count($thisContract['additionnalFees']); $i++) {
    if ($i == 0) {
        $amount = $prices_array[$thisBooking['formulaBooking'];
    } else {
        $amount += $prices_array[current($thisContract['additionnalFees'])];
        next($thisContract['additionnalFees']);
    }
}
$customerInvoice->amountInvoice = $amount;

$customerInvoice->idPartner = $thisContract->idPartner;
$customerInvoice->idCustomer = $thisContract->idCustomer;
$customerInvoice->idBooking = $thisContract->idBooking;
$customerInvoice->idContract = $thisContract->idContract;
$customerInvoice->idCar = $thisContract->idCar;

$uploadDirectory = 'uploadedFiles/customerInvoices/';
$extensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
$file = $_FILES['userfile']['name'];
$tempName = $_FILES['userfile']['tmp_name'];
$fileError = $_FILES['userfile']['error'];

if ($fileError == UPLOAD_ERR_OK) {   
    $extension = strtolower(pathinfo($file,PATHINFO_EXTENSION));
    if (in_array($extension, $extensions)) {
        $saveName = $customerInvoice->invoiceNumber.'-'.uniqid().'.'.$extension;
        move_uploaded_file($tempName, '../../'.$uploadDirectory.$saveName);
    } else {
        echo 'Le format de l\'image '. $file .' n\'est pas bon';
    }
} else {
    echo json_encode('Erreur avec le fichier image : '.$fileError);
}
$customerInvoice->invoiceNumber = $uploadDirectory.$saveName;

$result = $customerInvoice->createInvoice($customerInvoice);

if ($result) {
    echo json_encode([ "message" => "La facture a été éditée !" ]);
} else {
    echo json_encode([ "message" => "La facture n'a pas pu être éditée..." ]);
}