<?
class ContentsController extends MadController {
	function indexAction() {
		$get = $this->params;
		$model = $this->model;

		if( $get->layout ) {
			$this->layout->setFile( "layout/data/$get->layout.html" );
		}

		$this->view->get = $get;

		$query = new MadQuery( get_class($this->model) );

		if ( $get->searchWord ) {
			$query->where("contents like '%$get->searchWord%' or title like '%$get->searchWord%'");
		}

		$query->limit();

		$db = $this->db;
		$db->query( $query );
		$this->view->index = $db->fetchAll( $this->model );
	}
	function updateAction() {
		if ( ! isset( $this->session->user ) ) throw new Exception('need login');

		$query = new MadQuery( get_class($this->model) ) ;
		$query->update( $this->params->getData() )->exec();

		$this->js->replaceBack();
		die;
	}
	function viewAction() {
		$this->model->fetch( $this->params->id );
	}
	function writeAction() {
		if ( ! isset( $this->session->user ) ) throw new Exception('need login');

		$get = $this->params;
		$model = $this->model;

		$model->fetch( $get->id );
		if ( ! $model->id ) {
			$model->fetchDefault();
		}
		if ( $get->parentId ) {
			$model->parentId = $get->parentId;
		}
	}
	function saveAction() {
		if ( ! isset( $this->session->user ) ) throw new Exception('need login');

		$this->model->setData( $this->params )->save();
		$this->js->replaceBack();
		die;
	}
	function deleteAction() {
		if ( ! isset( $this->session->user ) ) throw new Exception('need login');

		$this->model->delete( $this->params->id );
		$this->js->replaceBack();
		die;
	}


	function migrateAction() {
		if ( ! isset( $this->session->user ) ) throw new Exception('need login');

		$table = 'Contents';
		$mg = $table . '_migrate';

		$query = "alter table $table rename to $mg";
		$this->db->exec( $query );
		$scheme = new MadScheme( $this->model );
		$this->db->exec( $scheme );

		$query = "PRAGMA table_info($mg)";
		$mgInfo = new MadData($this->db->query( $query)->fetchAll(PDO::FETCH_CLASS));

		$query = "PRAGMA table_info($table)";
		$info = new MadData($this->db->query( $query)->fetchAll(PDO::FETCH_CLASS));

		$columns = $mgInfo->dic('name')->intersect($info->dic('name')->getData() )->implode(',');

		$query = "insert into $table ($columns) select $columns from $mg";
		$result = $this->db->exec( $query );
		return $result;
	}
	function installAction() {
		if ( ! isset( $this->session->user ) ) throw new Exception('need login');

		$this->dropAction();
		$scheme = new MadScheme( $this->model );
		return $this->db->exec( $scheme );
	}
	function dropAction() {
		if ( ! isset( $this->session->user ) ) throw new Exception('need login');

		$query = "drop table " . get_class($this->model);
		return $this->db->exec( $query );
	}
}
