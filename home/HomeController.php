<?
class HomeController extends MadController {
	function indexAction() {
		$this->model->fetch($this->session->company->label);
	}
	function writeAction() {
		$this->model->fetch($this->session->company->label);
	}
	function saveAction() {
		$model = $this->model;

		$model->fetch($this->session->company->label);
		return $model->addData( $this->params )->save();
	}
	function deleteAction() {
		return $this->model->delete( $this->params->id );
	}
}
