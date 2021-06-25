<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

include_once "../../config/Database.php";
include_once "../../models/Customer.php";
include_once "../../models/Address.php";
include_once "../../models/Booking.php";

$db = new Database();
$conn = $db->connect();
$customer = new Customer($conn);
$address = new Address($conn);
// $booking = new Booking($conn);

if (isset($_GET['idCustomer'])) {
    $customer->idCustomer = $_GET['idCustomer'];
    $thisCustomer = $customer->searchCustomerById($customer);
    $address->idAddress = $thisCustomer['idBillingAddress'];
    $thisAddress = $address->searchAddressById($address);
    $thisCustomer['billingAddress'] = $thisAddress['address'];
    // $booking->idCustomer = $_GET['idCustomer'];
    // $bookings = $booking->searchBookingsByCustomer($bookings);
    // $counter = $bookings->rowCount();
    // if ($counter > 0) {
    //     $bookings_array = array();
    //     while ($row = $bookings->fetch()) {
    //         extract($row);
    //         if ($statusBooking = 'confirme') {
    //             $booking_item = [
    //                      "idBooking" => $idBooking,
    //                      "idCustomer" => $idCustomer,
    //                      "idPartner" => $idPartner,
    //                      "hoursForth" => $hoursForth,
    //                      "dateForth" => $dateForth,
    //                      "statusBooking" => $statusBooking,
    //                      "formulaBooking" => $formulaBooking,
    //                      "dateBack" => $dateBack,
    //                      "hoursBack" => $hoursBack,
    //                      "idCar" => $idCar,
    //                      "idAddressForth" => $idAddressForth,
    //                      "idAddressBack" => $idAddressBack,
    //                      "idAgency" => $idAgency,
    //                      "distanceForth" => $distanceForth,
    //                      "durationForth" => $durationForth,
    //                      "distanceBack" => $distanceBack,
    //                      "durationBack" => $durationBack,
    //                      "originBooking" => $originBooking,
    //                      "carProcess" => $carProcces,
    //                      "dateBooking" => $dateBooking,
    //             ];
    //             array_push($bookings_array, $booking_item);
    //         }
    //     }
        // if ($counter > 1) {
        //     $idBooking  = array_column($bookings_array, 'idBooking');
        //     array_multisort($idBooking, SORT_DESC, $bookings_array);
        //     array_splice($result, 0, -5);
        // }
    //     $result = $bookings_array;
    // }
    $result = [$thisCustomer, $bookings_array];
} else {
    if (isset($_GET['idPartner'])) {
        $customer->idPartner = $_GET['idPartner'];
        $customers = $customer->searchCustomersByPartner($customer);
    } else if (isset($_GET['statusCustomer'])) {
		$customer->statusCustomer = $_GET['statusCustomer'];
		$customers = $customer->listCustomerStatus($customer);
	} else {
        $customers = $customer->listCustomers();
    }
    $counter = $customers->rowCount();
    if ($counter > 0) {
        $customers_array = array();
        while ($row = $customers->fetch()) {
            extract($row);
            $address->idAddress = $idBillingAddress;
            $billingAddress = $address->searchAddressById($address);
            $customer_item = [
                 "idCustomer" => $idCustomer,
                 "billingAddress" => $billingAddress['address'],
                 "firstNameCustomer" => $firstNameCustomer,
                 "lastNameCustomer" => $lastNameCustomer,
                 "dateOfBirthdayCustomer" => $dateOfBirthdayCustomer,
                 "phoneCustomer" => $phoneCustomer,
                 "mailCustomer" => $mailCustomer
            ];
            array_push($customers_array, $customer_item);
        }
        $result = $customers_array;
    }
}

if (isset($result) && !empty($result)) {
    echo json_encode($result);
} else { 
    http_response_code(404); 
}