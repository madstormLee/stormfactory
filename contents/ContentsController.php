<?
class ContentsController extends MadController {
	function indexAction() {
		$get = $this->params;
		$model = $this->model;

		if( $get->layout ) {
			$this->layout->setFile( "layout/data/$get->layout.html" );
		}

		$this->view->get = $get;

		$query = new MadQuery( get_class($this->model) );

		if ( $get->searchWord ) {
			$query->where("contents like '%$get->searchWord%' or title like '%$get->searchWord%'");
		}

		$query->limit();

		$db = $this->db;
		$db->query( $query );
		$this->view->index = $db->fetchAll( $this->model );
	}
	function viewAction() {
		$this->model->fetch( $this->params->id );
	}
	function writeAction() {
		if ( ! isset( $this->session->user ) ) throw new Exception('need login');

		$get = $this->params;
		$model = $this->model;

		$model->fetch( $get->id );
		if ( ! $model->id ) {
			$model->fetchDefault();
		}
		if ( $get->domain ) {
			$model->domain = $get->domain;
		}
	}
	function saveAction() {
		if ( ! isset( $this->session->user ) ) throw new Exception('need login');

		return $this->model->setData( $this->params )->save();
	}
	function updateAction() {
		if ( ! isset( $this->session->user ) ) throw new Exception('need login');

		$query = new MadQuery( get_class($this->model) ) ;
		return $query->update( $this->params->getData() )->exec();
	}
	function deleteAction() {
		if ( ! isset( $this->session->user ) ) throw new Exception('need login');

		return $this->model->delete( $this->params->id );
	}
}
