<?
include 'mad/tools.php';

$config = MadConfig::getInstance();
$config->header = new MadView('layout/header.html');
$config->footer = new MadView('layout/footer.html');

$config->userLog = MadSessionUser::getInstance();
$config->css = MadCss::getInstance()
	->add('~/mad/foundation/css/normalize.css')
	->add('~/mad/foundation/css/foundation.min.css')
	->add('~/index/style.css');

$main = new MadView('index/index.html');

$layout = new MadView('layout/layout.html');
$layout->main = $main;

print $layout;
