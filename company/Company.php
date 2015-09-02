<?
class Company extends MadModel {
	public static function session() {
		$session = MadSession::getInstance();
		if ( ! $session->company ) {
			$session->company = new self;
		}

		$router = MadRouter::getInstance();
		if( count( $router->args ) > 0 && ! is_dir( $router->arg(0) ) ) {
			$router->company = $router->argShift(0);
		} elseif( isset($session->company->label) ) {
			$router->company = $session->company->label;
		} else {
			$router->company = 'stormfactory';
		}

		return $session->company;
	}
	public static function checkLogin() {
		$router = MadRouter::getInstance();
		$session = MadSession::getInstance();
		if ( isset( $session->user ) && !isset($session->company) && $router->component !== 'company' ) {
			;
			$router->replace( '~/company' );
		}
	}
	function fetch( $id='' ) {
		return $this->fetchLabel( $id );
	}
	function getIndex() {
		return new MadData;
	}
	function fetchLabel( $label = '' ) {
		if ( empty( $label ) ) {
			return false;
		}
		$query = new MadQuery( 'Group' );
		$query->where( "domain=:domain" );
		$query->where( "label=:label" );
		$statement = $this->getDb()->prepare( $query );
		$result = $statement->execute( array( 'domain' => 'company', 'label' => $label ) );
		$this->data = $statement->fetch(PDO::FETCH_ASSOC); 
		return $this;
	}
}
