<?
class GoogleOAuth extends MadData {
	private $url = 'https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=';
	private $id_token = '';
	function setIdToken( $id_token ) {
		$this->id_token = $id_token;
		return $this;
	}
	function auth() {
		$curl = new MadCurl( $this->url . $this->id_token );
		$result = $curl->get();
		$this->setData( json_decode($result) );
		return $this;
	}
}
