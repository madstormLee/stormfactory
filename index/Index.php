<?
class Index extends MadModel {
	function __construct( $id = '' ) {
		if ( empty( $id ) ) {
			$id = "day" . (date('z') + 1);
		}
		if ( ! is_file( "index/data/$id.json" ) ) {
			$id = 'preset';
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
		$days = 10;

		$data = new MadData;
		$start = date('z');
		$end = $start-10;
		if ( $end < 1 ) {
			$end =  1;
		}
		foreach( range( $start, $end, -1 ) as $day ) {
			$dayName = "day$day";
			$data->$dayName = new self( $dayName );
		}
		return $data;
	}
}
