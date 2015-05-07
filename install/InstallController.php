<?
class InstallController extends MadController {
	function indexAction() {
		$index = $this->model->getIndex();
		$this->view->index = $index;
	}
	function installAction() {
		if ( ! isset( $this->session->user ) ) throw new Exception('need login');

		$this->dropAction();

		$get = $this->params;
		$model = $this->model;

		$model->fetch( $get->file );
		return $model->install();
	}
	function installAction() {
		$scheme = new MadScheme( $this->model );
		return $this->db->exec( $scheme );
	}
	function uninstallAction() {
		$get = $this->params;
		$model = $this->model;

		$model->fetch( $get->file );
		return $model->uninstall();
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
	function dropAction() {
		if ( ! isset( $this->session->user ) ) throw new Exception('need login');

		$query = "drop table " . get_class($this->model);
		return $this->db->exec( $query );
	}
}
