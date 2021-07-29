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

$booking->idCustomer = '';
$booking->idPartner = '';
$booking->hoursForth = $decodedData->hoursForth;
$booking->dateForth = $decodedData->dateForth;
$booking->formulaBooking = '';
$booking->dateBack = $decodedData->dateBack;
$booking->hoursBack = $decodedData->hoursBack;
$booking->idCar = '';
$booking->idAddressForth = '';
$booking->idAddressBack = '';
$booking->idAgency = '';
$booking->distanceForth = '';
$booking->durationForth = '';
$booking->distanceBack = '';
$booking->durationBack = '';
$booking->priceBooking = '';
$booking->carProcess = '';
$booking->statusBooking = $decodedData->statusBooking;

$result = $booking->createBooking($booking);

if ($result) {
    echo json_encode([ "message" => "La période de vacances a été éditée !" ]);
} else {     
    echo json_encode([ "message" => "La période de vacances n'a pas pu être éditée..." ]);
}