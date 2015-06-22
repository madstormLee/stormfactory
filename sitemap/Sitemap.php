<?
class Sitemap extends MadModel {
	function getIndex() {
		$query = new MadQuery( 'Group' );
		$query->where( "domain='$this->domain'" )
			->limit();
		$db = $this->getDb();
		$db->query( $query );
		return $db->fetchAll( 'Layout' );
	}
	function fetch( $id='' ) {
		print $id;
	}
}
