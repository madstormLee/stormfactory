<?
class LayoutController extends MadController {
	function indexAction() {
		if ( ! $this->router->ajax ) {
			$view = $this->layout;
		} else {
			$view = $this->view;
		}
		$view->setFile($this->params->file);
		$view->main = '<textarea id="contents" name="contents" rows="30" ></textarea>';

		if ( $this->session->company ) {
			$domain = $this->session->company->label . '/sitemap';
		} else {
			$domain = 'sitemap';
		}
		$model = $this->model;

		$sitemap = new Sitemap;
		$sitemap->domain = $domain;

		$index = new MadTree( $sitemap->getIndex() );
		$view->sitemap = $index;
	}
	function listAction() {
	}
}
