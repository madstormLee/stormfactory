<?
class ProjectController extends MadController {
	function indexAction() {
	}
	function writeAction() {
		$this->model->fetch( $this->params->id );
	}
	function saveAction() {
		// $this->model->setSetting( "$this->component/model.json" );
		var_dump( $this->model->setData( $this->params )->save() );
		die;
	}
	function deleteAction() {
		return $this->model->delete( $this->params->id );
	}
}
