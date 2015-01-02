<?
class Index extends MadModel {
	function __construct( $id = '' ) {
		if ( empty( $id ) ) {
			$todayId = "day" . (date('z') + 1);
			if ( is_file( "index/data/$todayId.json" ) ) {
				$id = $todayId;
			} else {
				$id = 'preset';
			}
		}
		$this->fetch( $id );
	}
	function fetch( $id ) {
		$json = new MadJson( "index/data/$id.json" );
		if ( is_file( $json->contents ) ) {
			$json->contents = new MadView( $json->contents );
		} else {
			$json->contents = '';
		}
		if ( ! $json->id ) {
			$json->id = "day" . (date('z') + 1);
		}
		$this->data = $json->getData();
	}
	function getIndex() {
		$data = new MadData;
		$range = range( date('z'), 1, -1 );
		foreach( $range as $day ) {
			$dayName = "day$day";
			$data->$dayName = new self( $dayName );
		}
		return $data;
	}
}
