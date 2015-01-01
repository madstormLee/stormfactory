<?
include 'mad/tools.php';

$main = new MadView('index.html');

$layout = new MadView('layout.html');
$layout->main = $main;

print $layout;
