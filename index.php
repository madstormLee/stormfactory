<?
include 'mad/tools.php';

$main = new MadComponent('index');

$component = new MadComponent('mad/layout/ant');
$component->setAction('layout');

$layout = $component->getContents();
$layout->main = $main;

print $layout;
