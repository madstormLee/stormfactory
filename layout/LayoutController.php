<?
class LayoutController extends MadController {
	function indexAction() {
	}
	function listAction() {
	}
	function viewAction() {
		$this->view->setFile($this->params->file);
		$this->view->main = '<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />';

		// @todo refactory: this is from sitemap.
		$get = $this->params;
		if ( ! isset($get->parentId) ) {
			$get->parentId = 0;
		}
		if ( $this->session->company ) {
			$domain = $this->session->company->label . '/sitemap';
		} else {
			$domain = 'sitemap';
		}
		$this->model->domain = $domain;
		$this->model->parentId = $get->parentId;

		$query = new MadQuery( 'Group' );
		$query->where( "domain='$domain'" )
			->limit();
		$this->db->query( $query );
		$index = $this->db->fetchAll( $this->model );
		$index = new MadTree( $index );
		// printR( $index );
		$this->view->sitemap = $index;
	}
	function writeAction() {
	}
}
