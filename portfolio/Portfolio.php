<?
class Portfolio extends MadModel {
	function getIndex() {
		$data = new MadJson( __DIR__ .'/data.json' );
		return $data;
	}
}
