<?
class GroupController extends MadController {
	function indexAction() {
	}
	function listAction() {
		$db = $this->db;
		$get = $this->params;
		if ( ! isset($get->parentId) ) {
			$get->parentId = 0;
		}

		$query = new MadQuery( get_class($this->model) );
		$query->where("parentId=$get->parentId")
			->order("orderId")
			->limit();

		$db->query( $query );
		$index = new MadData( $db->fetchAll( $this->model ) );

		$this->view->index = $index;
	}
	function writeAction() {
		$db = $this->db;
		$get = $this->params;
		$model = $this->model;
		if ( ! isset($get->parentId) ) {
			$get->parentId = 0;
		}

		$model->parentId = $get->parentId;
		if ( $get->id ) {
			$model->fetch( $get->id );
		}
	}
	function saveAction() {
		return $this->model->setData( $this->params )->save();
	}
	function deleteAction() {
		return $this->model->delete( $this->params->id );
	}
}
