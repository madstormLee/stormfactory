<?
include 'mad/tools.php';

$config = MadConfig::getInstance();
$config->header = new MadView('header.html');
$config->footer = new MadView('footer.html');

$config->userLog = MadSessionUser::getInstance();
$config->css = MadCss::getInstance()
	->add('~/mad/foundation/css/normalize.css')
	->add('~/mad/foundation/css/foundation.min.css')
	->add('~/style.css');

$main = new MadView('index.html');

$layout = new MadView('layout.html');
$layout->main = $main;

print $layout;
