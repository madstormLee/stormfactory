<?
class Company extends MadModel {
	function getIndex() {
		return new MadData;
	}
	public static function checkLogin() {
		$router = MadRouter::getInstance();
		$session = MadSession::getInstance();
		if ( isset( $session->user ) && !isset($session->company) && $router->component !== 'company' ) {
			;
			$router->replace( '~/company' );
		}
	}
}
