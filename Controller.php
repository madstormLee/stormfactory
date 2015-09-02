<?
class Controller extends MadController {
	function indexAction() {
		$index = new MadJson('user/stormfactory.json');
		$this->view->index = $index;
	}
}
