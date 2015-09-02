<?
class Contents extends MadModel {
	protected $types = array();
	function getSetting( $type='' ) {
		if ( empty( $this->setting ) ) {
			$this->setSetting('contents/model.json');
		}
		if ( isset( $this->setting->$type ) ) {
			return $this->setting->$type;
		}
		return $this->setting;
	}
	function getStack( $parentId=0 ) {
		$query = "select * from Contents where parentId=$parentId and type='stack'";
		return $this->getDb()->query($query)->fetchAll(PDO::FETCH_CLASS);
	}
	function fetch( $id='' ) {
		if ( empty( $id ) ) {
			return $this->fetchDefault();
		}
		$query = new MadQuery(get_class($this));
		$query->where( "id=$id" );

		$db = $this->getDb();
		$this->data = $db->query( $query )->fetch(PDO::FETCH_ASSOC);
		return $this;
	}
	function fetchDefault() {
		$this->data = $this->getSetting()->dic('default')->getData();
		return $this;
	}
	function getStatuses() {
		return new MadJson('project/statuses.json');
	}
	function getCategories() {
		return $this->setting->category->options;
	}
	function insert() {
		$this->wDate = date('Y-m-d H:i:s');
		$this->uDate = date('Y-m-d H:i:s');

		$query = new MadQuery( get_class($this) );
		$query->insert( array_filter($this->data) );

		$db = $this->getDb();

		$statement = $db->prepare( $query );
		$result = $statement->execute( $query->data() );
		return $db->lastInsertId();
	}
	function update() {
		$this->uDate = date('Y-m-d H:i:s');

		$query = new MadQuery( get_class($this) );
		$query->update( $this->data );

		$db = $this->getDb();

		$statement = $db->prepare( $query );
		$result = $statement->execute( $query->data() );

		return $statement->rowCount();
	}
	function delete( $id='' ) {
		$query = "delete from Contents where id=?";

		$db = $this->getDb();

		$statement = $db->prepare( $query );
		$result = $statement->execute( array($id) );

		return $statement->rowCount();
	}
}
