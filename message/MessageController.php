<?
class MessageController extends MadController {
	function indexAction() {
	}
	function listAction() {
		$index = $this->model->getIndex();
		$userId = $this->session->user->userId;
		$index->getQuery()->where("`receiver` like '$userId'")->limit();
		$this->view->index = $index;
	}
	function sendListAction() {
		$index = $this->model->getIndex();
		$userId = $this->session->user->userId;
		$index->getQuery()->where("`sender` like '$userId'")->limit();
		$this->view->index = $index;
	}
	function writeAction() {
		$get = $this->params;
		$model = $this->model;
		$router = $this->router;

		$model->fetch($get->id);
		$model->sender = $this->session->user->userId;

		// domain will be added 
		$company = $this->session->company->label;
		$model->domain = "$router->project/$company/$router->component";
	}
	function saveAction() {
		return $this->model->setData( $this->params )->save();
	}
	function deleteAction() {
		return $this->model->delete( $this->params->id );
	}
}
