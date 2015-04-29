<?
class GroupController extends MadController {
	function indexAction() {
		$db = $this->db;
		$query = new MadQuery( get_class($this->model) );
		$query->where("type='stack'");
		$query->order("parentId");
		$query->limit();
		$db->query( $query );

		$data = $db->fetchAll( $this->model );

		$index = new MadTree( $data );

		$this->view->index = $index;
	}
	function installAction() {
	}
}
