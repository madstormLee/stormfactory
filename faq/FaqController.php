<?
class FaqController extends MadController {
	function init() {
		parent::init();
		$this->domain = 'faq/' . $this->session->company->label;
		$this->model->domain = $this->domain;
	}
	function indexAction() {
	}
	function writeAction() {
		$this->model->fetch($this->params->id);
	}
	function saveAction() {
		return $this->model->addData( $this->params )->save();
	}
	function deleteAction() {
		return $this->model->delete( $this->params->id );
	}
}
