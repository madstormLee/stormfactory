<?
class Install extends MadModel {
	protected $file='';
	function __construct( $file='' ) {
		if ( ! empty( $file ) ) {
			$this->fetch( $file );
		}
	}
	function getTableName( $file = '' ) {
		if ( empty( $file ) ) {
			$file = $this->file;
		}
		return ucFirst(basename( dirname($file) ));
	}
	function fetch( $file = '' ) {
		if ( ! is_file( $file ) ) {
			throw new Exception(sprintf( 'no file : %s', $file) );
		}
		$this->file = $file;
		$json = new MadJson( $file );
		$this->data = $json->getData();
		return $this;
	}
	function uninstall() {
		$table = $this->getTableName();
		$query = "drop table `$table`";
		return $this->getDb()->exec( $query );
	}
	function install() {
		$model = new MadModel;
		$model->setName( $this->getTableName( $this->file ) );
		$model->setSetting( $this->file );
		$scheme = new MadScheme( $model );
		return $this->getDb()->exec( $scheme );
	}
	function getColumns( $table  ) {
		if ( ! $this->isInstall( $table ) ) {
			return new MadData;
		}
		$query = "PRAGMA table_info(`$table`)";
		$columns = $this->getDb()->query($query)->fetchAll(PDO::FETCH_CLASS);
		return new MadData( $columns );
	}
	function getIndex() {
		$rv = new MadData;

		$data = globR('model.json');
		$data = array_merge( $data, globR('mad/*/model.json') );

		foreach( $data as $file ) {
			$table = $this->getTableName( $file );
			$model = new MadModel;
			$model->setSetting( $file );
			$install = $this->isInstall( $table );
			$rv->add( new MadData(array(
				'install' => $install,
				'table' =>	$table,
				'file' =>	$file,
				'model' =>	$model,
				'columns' =>	$this->getColumns( $table ),
			)));
		}
		return $rv;
	}
	function isInstall( $table ) {
		$query = new MadQuery($table);
		return $query->isTable();
	}
}
