<?
class CompanyController extends MadController {
	function indexAction() {
		$query = new MadQuery( 'Group' );
		$query->where( "domain like 'company'" );
		$query->limit();
		print $query;

		$result = $this->db->query( $query );
		varDump( $result );
		$this->view->index = $this->db->fetchAll( $this->model );
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
