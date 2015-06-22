<?
class Faq extends MadModel {
	protected $name = 'Contents';

	// @todo: this is very bad way, i think...
	function setName( $name = '' ) {
		$this->name = 'Contents';
		return $this;
	}
	function getIndex() {
		$index = parent::getIndex();
		$index->getQuery()->where( "`domain` like '$this->domain'" )->limit();
		return $index;
	}
}
