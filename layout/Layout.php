<?
class Layout extends MadModel {
	function getIndex() {
		return new MadDir('layout/data', '*.html');
	}
}
