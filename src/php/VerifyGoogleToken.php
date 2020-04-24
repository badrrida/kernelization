<?php
require_once '../../../google-api-php-client-2.2.2/vendor/autoload.php';

$id_token = $_POST["idtoken"];
$email = $_POST["email"];
$name = $_POST["name"];

$client = new Google_Client(['client_id' => '322206757489-gaeist4me4ih4pm333m7ei3impir46oq']);  // Specify the CLIENT_ID of the app that accesses the backend
#$client->setAccessToken($id_token);
$payload = $client->verifyIdToken($id_token);
echo $payload;
if ($payload) {
	$userid = $payload['sub'];
	// If request specified a G Suite domain:
	//$domain = $payload['hd'];
  
	$mysqli = new mysqli('localhost', 'root', '?W3_2%sX/2[ug]PY', 'kernelizationusers');
	$result = $mysqli->query("SELECT id FROM customer WHERE google_tokenid = " + $id_token);
	echo $result;
	if($result->num_rows == 0) {
		echo "account exists";
	} else {
		echo "account does not exist";
		$mysqli->query("INSERT INTO customer (email, name, google_tokenid) VALUES (" + $email + ", " + $name + ", " + $id_token + "')");
	}
	$mysqli->close();

} else {
  // Invalid ID token
}
?>