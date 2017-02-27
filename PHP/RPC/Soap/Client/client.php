<?php
try {
	$client = new SoapClient ( "http://localhost/Web_services/PHP/RPC/Soap/Server/webservice.wsdl", array (
			'trace' => true,
			'exceptions' => true 
	) );
	
	$params = new \stdClass ();
	$params->Credentails = new \stdClass ();
	$params->Credentails->Username = "Username";
	$params->Credentails->Password = "Password";
	
	$response = $client->Authentication ( $params );
	print_r ( json_encode ( $response, true ) );
	
	$params = new \stdClass ();
	$params->Name = new \stdClass ();
	$params->Name->Firstname = "";
	$params->Name->Middlename = "";
	$params->Name->Lastname = "";
	
	$params->Address = new \stdClass ();
	$params->Address->Line1 = "";
	$params->Address->City = "";
	$params->Address->State = "";
	$params->Address->Country = "";
	$params->Address->Zip = "";
	
	$params->Phone = new \stdClass ();
	$params->Phone->Phonenumber = "";
	
	$response = $client->AddressValidation ( $params );
	print_r ( json_encode ( $response, true ) );
} catch ( Exception $e ) {
	print_r ( $e->getMessage () );
}
