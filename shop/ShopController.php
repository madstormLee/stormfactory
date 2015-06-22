<?
class ShopController extends MadController {
	function indexAction() {
		$this->view->index = $this->model->getIndex();
	}
	function writeAction() {
		$this->model->fetch($this->params->id);
	}
	function saveAction() {
		return $this->model->setData( $this->params )->save();
	}
	function deleteAction() {
		return $this->model->delete( $this->params->id );
	}
}
