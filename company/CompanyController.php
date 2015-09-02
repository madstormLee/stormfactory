<?
class CompanyController extends MadController {
	function indexAction() {
		$query = new MadQuery( 'Group' );
		$query->where( "domain like 'company'" );
		$query->limit();

		$result = $this->db->query( $query );
		$this->view->index = $this->db->fetchAll( $this->model );
	}
	function statusAction() {
		if ( $this->params->company ) {
			$company = $this->params->company;
		} else {
			$company = $this->router->company;
		}
		$this->model->fetchLabel( $company );
	}
	function selectAction() {
		$group = new Group;
		$group->fetch( $this->params->id);
		$this->session->company = $group;
		return true;
	}
	function writeAction() {
	}
	function saveAction() {
		$model = new Group;
		return $model->setData( $this->params )->save();
	}
	function deleteAction() {
		$model = new Group;
		return $model->delete( $this->params->id );
	}
}
