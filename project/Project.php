<?
// todo: MadModel setting to definition?
class Project extends MadModel {
	protected $types = array();

	// refactoring this with MadScheme.
	function getScheme() {
		$data = array();
		foreach( $this->setting as $row ) {
			if ( $row->id == 'id' && $row->extra == 'auto_increment' ) {
				$data[] = "`$row->id` $row->type primary key";
				continue;
			}
			$type = $this->getType( $row->type );
			$default = (isset($row->default))?"default '$row->default'":'';
			$comment = (isset($row->label))?"comment '$row->label'":'';
			$data[] = "`$row->name` $type $default $comment";
		}
		$definition = implode( ",\n", $data );
		$table = get_class($this);
		$query = "create table `$table`	( $definition );";
		print nl2br( $query );
		die;
		return $query;
	}
	function initType() {
		$this->types = new MadJson('mad/component/model/types.json');
	}
	function getType( $type ) {
		if ( empty( $this->types ) ) {
			$this->initType();
		}
		return isset($this->types->$type)?$this->types->$type:'varchar';
	}



	function getIndex() {
		$db = $this->getDb();
		$query = "select * from Project";
		$db->query( $query );
		return $db->fetchAll( $this );
	}
	function getPersona() {
		return new MadData;
	}
	function getStatuses() {
		return new MadJson('project/statuses.json');
	}
	function getCategories() {
		return new MadJson('project/categories.json');
	}
	function save() {
		if ( $this->id ) {
			return $this->update();
		}
		return $this->insert();
	}
	function getDb() {
		return MadConfig::getInstance()->db;
	}
	function insert() {
		$this->wDate = date('Y-m-d H:i:s');
		$this->uDate = date('Y-m-d H:i:s');

		$query = new MadQuery( get_class($this) );
		$query->insert( $this->data );

		$db = $this->getDb();
		if ( !$statement =$db->prepare( $query ) ) {
			throw new Exception('wrong query.');
		}
		print $statement->queryString;
		printR( $this->values() );
		die;
		$result = $statement->execute( $this->values() );
		printR( $statement->errorInfo() );
		return $result;
	}
	function update() {
		return 'update';
	}
}
