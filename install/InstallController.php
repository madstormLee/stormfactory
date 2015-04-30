<?
class InstallController extends MadController {
	function indexAction() {
		$index = $this->model->getIndex();
		$this->view->index = $index;
	}
	function installAction() {
		$get = $this->params;
		$model = $this->model;

		$model->fetch( $get->file );
		return $model->install();
	}
	function uninstallAction() {
		$get = $this->params;
		$model = $this->model;

		$model->fetch( $get->file );
		return $model->uninstall();
	}
}
