<?php
class RequestHandler {
	public function getCall($request) {
		$response = array("Method"=>"Get");
		return $response;
	}
	public function postCall() {
		$response = array("Method"=>"Post");
		return $response;
	}
	public function deleteCall() {
		$response = array("Method"=>"Delete");
		return $response;
	}
	public function putCall() {
		$response = array("Method"=>"Put");
		return $response;
	}
}
?>
