<?
class PortfolioController extends MadController {
	function indexAction() {
		$get = $this->params;
		if ( ! $get->mode ) {
			$get->mode = 'orbit';
		}
	}
	function writeAction() {
		$this->model->fetch( $this->params->id );
	}
	function saveAction() {
		return $this->model->setData( $this->params )->save();
	}
	function deleteAction() {
		return $this->model->delete( $this->params->id );
	}
}
