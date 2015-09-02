<?
class ContentsController extends MadController {
	function indexAction() {
		$get = $this->params;
		$query = $this->model->getIndex()->getQuery();

		if ( $get->searchWord ) {
			$query->where("contents like '%$get->searchWord%' or title like '%$get->searchWord%'");
		}

		$query->limit();

	}
	function viewAction() {
		$this->model->fetch( $this->params->id );
	}
	function writeAction() {
		$get = $this->params;
		$model = $this->model;

		$model->fetch( $get->id );
		if ( ! $get->domain ) {
			$get->domain = $this->component;
		}
		$model->domain = $get->domain;
	}
	function saveAction() {
		return $this->model->setData( $this->params )->save();
	}
	function deleteAction() {
		return $this->model->delete( $this->params->id );
	}
}
