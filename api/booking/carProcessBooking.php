<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With");

include_once "../../config/Database.php";
include_once "../../models/Booking.php";

$db = new Database();
$conn = $db->connect();
$booking = new Booking($conn);

$decodedData = json_decode(file_get_contents("php://input"));

$booking->idBooking = $decodedData->idBooking;
$booking->carProcess = $decodedData->carProcess;   

if (!empty($decodedData->idBooking)) {
    $result = $booking->updateBookingCarProcess($booking);
}

if ($result) {
    echo json_encode(["message" => "Le process a été avancé !"]);
} else {
    echo json_encode(["message" => "Le process n'a pas pu être avancé !"]);
}
