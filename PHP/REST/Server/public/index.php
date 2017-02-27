<?php
include "../Helpers/RequestHandler.php";
include "../Helpers/ResponseHandler.php";
class API extends RequestHandler {

	/**
	 * Property: method
	 * The HTTP method this request was made in, either GET, POST, PUT or DELETE
	 */
	protected $method;

	/**
	 * Property: request
	 * Request inputs
	 */
	protected $requestdata;

	/**
	 * Property: response
	 */
	protected $response;

	/**
	 * Property: format
	 */
	protected $format;

	/**
	 * Property: status
	 */
	protected $status = 200;

	/**
	 * Constructor: __construct
	 * Allow for CORS, assemble and pre-process the data
	 */
	public function __construct($request) {
		header ( "Access-Control-Allow-Orgin: *" );
		header ( "Access-Control-Allow-Methods: *" );
		
		$username = 'root';
		$password = 'root';
		$response =  $this->Authentication($username, $password);
		error_log("Hello");
		if($response){
			$this->method = isset($_SERVER ['REQUEST_METHOD']) ? $_SERVER ['REQUEST_METHOD'] : "";
			$this->format = isset($_SERVER ['HTTP_ACCEPT']) ? $_SERVER ['HTTP_ACCEPT'] : "";
			
			if ($this->method == 'POST' && array_key_exists ( 'HTTP_X_HTTP_METHOD', $_SERVER )) {
				if ($_SERVER ['HTTP_X_HTTP_METHOD'] == 'DELETE') {
					$this->method = 'DELETE';
				} else if ($_SERVER ['HTTP_X_HTTP_METHOD'] == 'PUT') {
					$this->method = 'PUT';
				} else {
					throw new Exception ( "Unexpected Header" );
				}
			}
			
			switch ($this->method) {
				case 'GET' :
					$this->response = $this->getCall ($request);
					break;
				case 'POST' :
					$this->response = $this->postCall ($request);
					break;
				case 'DELETE' :
					$this->response = $this->deleteCall ($request);
					break;
				case 'PUT' :
					$this->response = $this->putCall ($request);
					break;
				default :
					$this->status = 405;
					break;
			}
		}else{
			$this->status = 401;
		}
		$reponseHandler = new ResponseHandler ();
		$reponseHandler->setResponse ( $this->format, $this->status, $this->response );
	}
	private function Authentication($username, $password) {
		if ($username == "root" && $password == "root") {
			return 1;
		}
		return 0;
	}
}
$rest = new API ( $_REQUEST );
