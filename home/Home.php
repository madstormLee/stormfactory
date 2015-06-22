<?
class Home extends MadJson {
	function fetch( $id ) {
		$file = "home/data/$id.json";
		$this->load( $file );
		if ( isset($this->layout) ) {
			$this->layoutUrl = "~/layout?file=$this->layout";
		}
		return $this;
	}
	function save() {
		if ( empty($this->wDate) ) {
			$this->wDate = date('Y-m-d H:i:s');
		}
		$this->uDate = date('Y-m-d H:i:s');
		return parent::save();
	}
	function getLayouts() {
		$layout = new Layout;
		return $layout->getIndex();
	}
}
