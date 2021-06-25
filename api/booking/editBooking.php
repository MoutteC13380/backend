<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With");

include_once "../../config/Database.php";
include_once "../../models/Booking.php";

$db = new Database();
$conn = $db->connect();
$booking = new Booking($conn);

$decodedData = json_decode(file_get_contents("php://input"));

$booking->idCustomer = $decodedData->idCustomer;
$booking->idPartner = $decodedData->idPartner;
$booking->hoursForth = $decodedData->hoursForth;
$booking->dateForth = $decodedData->dateForth;
$booking->formulaBooking = $decodedData->formulaBooking;
$booking->dateBack = $decodedData->dateBack;
$booking->hoursBack = $decodedData->hoursBack;
$booking->idCar = $decodedData->idCar;
$booking->idAddressForth = $decodedData->idAddressForth;
$booking->idAddressBack = $decodedData->idAddressBack;
$booking->idAgency = $decodedData->idAgency;
$booking->distanceForth = $decodedData->distanceForth;
$booking->durationForth = $decodedData->durationForth;
$booking->distanceBack = $decodedData->distanceBack;
$booking->durationBack = $decodedData->durationBack;
$booking->priceBooking = $decodedData->priceBooking;
$booking->carStatus = $decodedData->carStatus;


if (!empty($decodedData->idBooking)) {
    $booking->idBooking = $decodedData->idBooking;
    $result = $booking->updateBooking($booking);
} else {
    $booking->statusBooking = $decodedData->statusBooking;
    $result = $booking->createBooking($booking);
}

if ($result) {
    echo json_encode([ "message" => "La réservation a été éditée !" ]);
} else {     
    echo json_encode([ "message" => "La réservation n'a pas pu être éditée..." ]);
}