<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With");

include_once "../../config/Database.php";
include_once "../../models/Customer.php";
include_once "../../models/Address.php";

$db = new Database();
$conn = $db->connect();
$customer = new Customer($conn);
$billingAddress = new Address($conn);

$decodedData = json_decode(file_get_contents("php://input"));

$customer->firstNameCustomer = $decodedData->firstNameCustomer;
$customer->lastNameCustomer = $decodedData->lastNameCustomer;
$customer->dateOfBirthdayCustomer = $decodedData->dateOfBirthdayCustomer;
$customer->phoneCustomer = $decodedData->phoneCustomer;
$customer->mailCustomer = $decodedData->mailCustomer;
if (isset($decodedData->idCustomer)) {
	$customer->idCustomer = $decodedData->idCustomer;
} else {
	$customerExist = $customer->searchCustomerByData($customer);
	if ($customerExist) {
		$customer->idCustomer = $customerExist['idCustomer'];
	}
}

$billingAddress->address = $decodedData->addressBillingCustomer;
$addressBillingCustomer = $billingAddress->searchAddressByAddress($billingAddress);
if (count($addressBillingCustomer) != 0) {
	extract($addressBillingCustomer);
	$customer->idBillingAddress = $idAddress;
} else {
	$addressBillingCustomer = $billingAddress->createAddress($billingAddress);
	$customer->idBillingAddress = $billingAddressCustomer['idAddress'];
}

if (!is_null($customer->idCustomer)) {
	if (isset($decodedData->statusCustomer)) {
		$customer->statusCustomer = $decodedData->statusCustomer;
		$result = $customer->updateStatusCustomer($customer);
	} else if (isset($decodedData->idPartner)) {
		$customer->idPartner = $decodedData->idPartner;
		$result = $customer->bindPartnerToCustomer($customer);
	} else {
	    $result = $customer->updateCustomer($customer);
	}
} else {
	$customer->statusCustomer = $decodedData->statusCustomer;
	if (isset($decodedData->password)) {
		$customer->mixedPassword = $decodedData->password;
	} else {
	 	$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
 		$maxLength = strlen($chars);
 		$randomStr = '';
 		for ($i = 0; $i < 30; $i++) {
			$randomStr .= $chars[rand(0, $maxLength - 1)];
 		}
 		$customer->mixedPassword = $randomStr;
 		//TODO Envoi d'un mail au client pour son mot de passe 
 		// $randomStr est une cha??ne de caract??re al??atoire qui va ??tre utilis??e comme mot de passe temporaire, elle sera envoy??e en get dans le lien
	}
    $result = $customer->createCustomer($customer);
	$customer->bindIdBillingAddress($customer);
    if (isset($decodedData->idPartner)) {
    	$customer->idPartner = $decodedData->idPartner;
    	$customer->bindPartnerToCustomer($customer);
    }
}

if ($result) {
    echo json_encode([ "message" => "Le client a ??t?? ??dit??..." ]);
} else {
    echo json_encode([ "message" => "Le client n'a pas pu ??tre ??dit??..." ]);
}