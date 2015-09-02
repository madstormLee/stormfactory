<?
class Layout extends MadModel {
	private $layout = "layout/data/index.html";

	function getIndex() {
		return new MadDir('layout/data', '*.html');
	}
	function setLayout( $layout='' ) {
		if ( ! empty( $layout ) ) {
			$layout = "layout/data/$layout.html";
			if ( is_file( $layout ) ) {
				$this->layout = $layout;
			}
		}
		return $this;
	}
	function getLayout() {
		return $this->layout;
	}
}
