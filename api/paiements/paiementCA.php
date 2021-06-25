<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With");
// include_once "../../config/Database.php";
// include_once "../../models/OptionsCA.php";

// $db = new Database();
// $conn = $db->connect();
// $dataPayment = new OptionsCA($conn);

//Données Mouttec
// $mouttecData = $dataPayment->getPayboxData();
// $pbx_site = $mouttecData['site'];
// $pbx_rang = $mouttecData['rang'];
// $pbx_identifiant = $mouttecData['identifiant'];
// $hmackey = $mouttecData['keyPaybox']; 

//Données commande
// $pbx_cmd = $_POST['cmd'];
// $pbx_porteur = $_POST['emailCustomer'];
// $pbx_total = str_replace([",", "."], "", $_POST['totalAmount']);

// $curl = curl_init('https://preprod-tpeweb.e-transactions.fr/php/');
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($curl, CURLOPT_COOKIESESSION, true);

//Données de test
$pbx_site = '2695713';
$pbx_rang = '001';
$pbx_identifiant = '38004508';
$pbx_total = str_replace([",", "."], "", '120,50');
$hmackey = '967DF2AFD7B2498CD2BBA74412AE46E2E473937FEF43A80DD8EAA21B24656681EEB92D4F8C56096A9068E0464DF6C1DABEB53F836951D489ABEE10C7139E234E';
$pbx_cmd = '123';
$pbx_porteur = 'ya.lamour@gmail.com';

// Paramétrage de l'url de retour back office site (notification de paiement IPN) :
$pbx_repondre_a = 'https://mouttec.fr';
// Paramétrage des données retournées via l'IPN :
$pbx_retour = 'Mt:M;Ref:R;Auto:A;Erreur:E';
// Paramétrage des urls de redirection navigateur client après paiement :
$pbx_effectue = 'https://mouttec.fr/backend/api/paiements/accepte.php';
$pbx_annule = 'https://mouttec.fr/backend/api/paiements/annule.php';
$pbx_refuse = 'https://mouttec.fr/backend/api/paiements/refuse.php';
// On récupère la date au format ISO-8601 :
$dateTime = date("c");

$pbx_nb_produit = '5';
$pbx_shoppingcart = "
<?xml version=\"1.0\" encoding=\"utf-8\"?>
	<shoppingcart>
		<total>
			<totalQuantity>".$pbx_nb_produit."</totalQuantity>
		</total>
	</shoppingcart>";

// Valeurs envoyées dans PBX_BILLING :
$pbx_prenom_fact = 'Yannick';
$pbx_nom_fact = 'Lamour';
$pbx_adresse1_fact = '14 avenue Saint Maur';
$pbx_adresse2_fact = '';
$pbx_zipcode_fact = '34000';
$pbx_city_fact = 'Montpellier';
$pbx_country_fact = '250';
// Construction de PBX_BILLING :
$pbx_billing = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<Billing>
	<Address>
		<FirstName>".$pbx_prenom_fact."</FirstName>"."
		<LastName>".$pbx_nom_fact."</LastName>
		<Address1>".$pbx_adresse1_fact."</Address1>"."
		<Addresse2>".$pbx_adresse2_fact."</Addresse2>
		<ZipCode>".$pbx_zipcode_fact."</ZipCode>"."
		<City>".$pbx_city_fact."</City>
		<CountryCode>".$pbx_country_fact."</CountryCode>"."
	</Address>
</Billing>";

// --------------- SÉLÉCTION DE L'ENVIRRONEMENT ---------------
// Recette (paiements de test)  :
$urletrans ="https://recette-tpeweb.e-transactions.fr/php/";

// Production (paiements réels) :
// URL principale :
// $urletrans ="https://tpeweb.e-transactions.fr/php/";
// URL secondaire :
// $urletrans ="https://tpeweb1.e-transactions.fr/php/";

// --------------- TRAITEMENT DES VARIABLES ---------------

// On crée la chaîne à hacher sans URLencodage
$msg = "PBX_SITE=".$pbx_site.
"&PBX_RANG=".$pbx_rang.
"&PBX_IDENTIFIANT=".$pbx_identifiant.
"&PBX_TOTAL=".$pbx_total.
"&PBX_DEVISE=978".
"&PBX_CMD=".$pbx_cmd.
"&PBX_PORTEUR=".$pbx_porteur.
"&PBX_REPONDRE_A=".$pbx_repondre_a.
"&PBX_RETOUR=".$pbx_retour.
"&PBX_EFFECTUE=".$pbx_effectue.
"&PBX_ANNULE=".$pbx_annule.
"&PBX_REFUSE=".$pbx_refuse.
"&PBX_HASH=SHA512".
"&PBX_TIME=".$dateTime.
"&PBX_SHOPPINGCART=".$pbx_shoppingcart.
"&PBX_BILLING=".$pbx_billing;
// echo $msg;

// Si la clé est en ASCII, On la transforme en binaire
$binKey = pack("H*", $hmackey);
// On calcule l’empreinte (à renseigner dans le paramètre PBX_HMAC) grâce à la fonction hash_hmac et //
// la clé binaire
// On envoi via la variable PBX_HASH l'algorithme de hachage qui a été utilisé (SHA512 dans ce cas)
// Pour afficher la liste des algorithmes disponibles sur votre environnement, décommentez la ligne //
// suivante
// print_r(hash_algos());
$hmac = strtoupper(hash_hmac('sha512', $msg, $binKey));
// La chaîne sera envoyée en majuscule, d'où l'utilisation de strtoupper()
// On crée le formulaire à envoyer
// ATTENTION : l'ordre des champs dans le formulaire est extrêmement important, il doit
// correspondre exactement à l'ordre des champs dans la chaîne hachée.
?>
<!------------------ ENVOI DES INFORMATIONS A e-Transactions (Formulaire) ------------------>
<form method="POST" action="<?php echo $urletrans; ?>">
<input type="hidden" name="PBX_SITE" value="<?php echo $pbx_site; ?>">
<input type="hidden" name="PBX_RANG" value="<?php echo $pbx_rang; ?>">
<input type="hidden" name="PBX_IDENTIFIANT" value="<?php echo $pbx_identifiant; ?>">
<input type="hidden" name="PBX_TOTAL" value="<?php echo $pbx_total; ?>">
<input type="hidden" name="PBX_DEVISE" value="978">
<input type="hidden" name="PBX_CMD" value="<?php echo $pbx_cmd; ?>">
<input type="hidden" name="PBX_PORTEUR" value="<?php echo $pbx_porteur; ?>">
<input type="hidden" name="PBX_REPONDRE_A" value="<?php echo $pbx_repondre_a; ?>">
<input type="hidden" name="PBX_RETOUR" value="<?php echo $pbx_retour; ?>">
<input type="hidden" name="PBX_EFFECTUE" value="<?php echo $pbx_effectue; ?>">
<input type="hidden" name="PBX_ANNULE" value="<?php echo $pbx_annule; ?>">
<input type="hidden" name="PBX_REFUSE" value="<?php echo $pbx_refuse; ?>">
<input type="hidden" name="PBX_HASH" value="SHA512">
<input type="hidden" name="PBX_TIME" value="<?php echo $dateTime; ?>">
<input type="hidden" name="PBX_SHOPPINGCART" value="<?php echo htmlspecialchars($pbx_shoppingcart); ?>">
<input type="hidden" name="PBX_BILLING" value="<?php echo htmlspecialchars($pbx_billing); ?>">
<input type="hidden" name="PBX_HMAC" value="<?php echo $hmac; ?>">
<input type="submit" value="Envoyer">
</form>