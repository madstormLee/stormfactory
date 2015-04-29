<?
class Install extends MadModel {
	function getIndex() {
		$rv = globR('model.json');
		return $rv;
	}
}
