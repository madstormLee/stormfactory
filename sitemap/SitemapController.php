<?
class SitemapController extends MadController {
	function indexAction() {
		$index = new MadJson( 'sitemap.json' );
		$this->view->index = $index;
	}
	// todo: different with index - use Group.
	function listAction() {
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
			->where("parentId='$get->parentId'")
			->limit();
		$this->db->query( $query );
		$index = $this->db->fetchAll( $this->model );
		$this->view->index = $index;
	}
	function addAction() {
		$model = $this->model;
		if ( $this->session->company ) {
			$domain = $this->session->company->label . '/sitemap';
		} else {
			$domain = 'sitemap';
		}
		$model->domain = $domain;
	}
	function tempAction() {
		$model = new Group;
		return $model->setData( $this->params )->save();
	}
	function treeAction() {
		$this->js->addFirst('/mad/js/prototype');
		$this->main->sitemap = $this->sitemap;

		$mvcManager = new MadView('views/MvcManager/widget');
		$mvcManager->controllers = new Controllers;
		$this->main->mvcManager = $mvcManager;

		return $this->main;
	}
	function viewAction() {
		$current = $this->sitemap->getPath( $this->get->href );
		$this->main->current = $current;
		return $this->main;
	}
	function writeAction() {
	}
	function addSubAction() {
		$sitemap = $this->sitemap;
		$sitemap->addSub( $this->get->current, $this->post );
		$sitemap->save();
		$this->js->replace('back');
	}
	function saveAction() {
		$target = 'sitemap.json';
		$sitemap = new Sitemap( $target );
		$sitemap->setFromDl( $this->post->content );
		$sitemap->save();
	}
	function deleteAction() {
		$this->sitemap->removePath( $this->get->href )
			->save();
		$this->js->replace('back');
	}
}
