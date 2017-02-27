<?php
ignore_user_abort ( true );
ini_set ( "soap.wsdl_cache_enabled", "1" ); // enabling WSDL cache
ini_set ( 'soap.wsdl_cache_ttl', '300' );
header ( 'Content-Type: text/xml' );
class ServiceCalls {
	public function Authentication($requestParams) {
		$result = array (
				"AuthenticationResponse" => array (
						"Status" => true,
						"SessionID" => "" 
				) 
		);
		return $result;
	}
	public function AddressValidation($requestParams) {
		$requestParams = json_decode ( json_encode ( $requestParams, true ), true );
		error_log ( print_r ( $requestParams, true ) );
		$url = "address.xml";
		$xml_data = file_get_contents ( "$url" );
		$addresses = json_decode ( json_encode ( simplexml_load_string ( $xml_data ), true ), true );
		$i = $j = $k = 0;
		$result = array ();
		foreach ( $addresses ["address"] as $address ) {
			if (strtolower ( $requestParams ["Name"] ["Firstname"] ) == strtolower ( $address ["firstname"] )) {
				$i += 1;
			}
			if (strtolower ( $requestParams ["Name"] ["Middlename"] ) == strtolower ( $address ["middlename"] )) {
				$i += 1;
			}
			if (strtolower ( $requestParams ["Name"] ["Lastname"] ) == strtolower ( $address ["lastname"] )) {
				$i += 1;
			}
			if (strtolower ( $requestParams ["Address"] ["Line1"] ) == strtolower ( $address ["line1"] )) {
				$j += 1;
			}
			if (strtolower ( $requestParams ["Address"] ["City"] ) == strtolower ( $address ["city"] )) {
				$j += 1;
			}
			if (strtolower ( $requestParams ["Address"] ["State"] ) == strtolower ( $address ["state"] )) {
				$j += 1;
			}
			if (strtolower ( $requestParams ["Address"] ["Zip"] ) == strtolower ( $address ["zip"] )) {
				$j += 1;
			}
			if (strtolower ( $requestParams ["Address"] ["country"] ) == strtolower ( $address ["country"] )) {
				$j += 1;
			}
			if (urldecode ( $requestParams ["Phone"] ["Phonenumber"] ) == $address ["phone"]) {
				$k += 1;
			}
		}
		
		$result ['Name'] = ($i > 1) ? true : false;
		$result ['Address'] = ($j > 4) ? true : false;
		$result ['Phone'] = ($k > 0) ? true : false;
		$response = array (
				"AddressValidationResponse" => array (
						"NameResponse" => $result ['Name'],
						"AddressResponse" => $result ['Address'],
						"PhoneResponse" => $result ['Phone'] 
				) 
		);
		return $response;
	}
}
class SoapExceptionHandler {
	private $service;
	public function __construct($service) {
		$this->service = $service;
	}
	public function __call($method, $requestParams) {
		try {
			return call_user_func_array ( array (
					$this->service,
					$method 
			), $requestParams );
		} catch ( Exception $e ) {
			throw new SOAPFault ( 'SERVER', 'Application Error' );
		}
	}
}
/*
 * if (isset ( $_GET ['wsdl'] )) {
 * $params = array (
 * "uri" => "localhost/Git_files/Web_services/RPC/Soap/Server/api.php",
 * 'soap_version' => SOAP_1_2,
 * 'actor' => "http://example.org/ts-tests/C",
 * 'style' => SOAP_RPC,
 * 'use' => SOAP_LITERAL,
 * 'encoding' => 'ISO-8859-1'
 * );
 * $server = new SOAPServer ( 'webservice.wsdl', $params );
 * $server->setObject ( new SoapExceptionHandler ( new ServiceCalls () ) );
 * $server->handle ();
 * } else {
 */
$params = array (
		"uri" => "localhost/Web_services/PHP/RPC/Soap/Server/index.php",
		'soap_version' => SOAP_1_2,
		'actor' => "http://example.org/ts-tests/C",
		'style' => SOAP_RPC,
		'use' => SOAP_LITERAL,
		'encoding' => 'ISO-8859-1' 
);
$server = new SOAPServer ( 'webservice.wsdl', $params );
$server->setObject ( new SoapExceptionHandler ( new ServiceCalls () ) );
$server->handle ();
/* } */
