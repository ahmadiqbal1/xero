<?php 
$path = 'application/modules/sitedoctor/assets/bootstrap/css/bootstrap.css';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$data = preg_replace("#font-family:.*?;#si", "", $data);
echo "<style>".$data."</style>";

$path = 'application/modules/sitedoctor/assets/css/style.css';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$data = preg_replace("#font-family:.*?;#si", "", $data);
echo "<style>".$data."</style>";

$path = 'assets/css/style.css';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$data = preg_replace("#font-family:.*?;#si", "", $data);
echo "<style>".$data."</style>";

$path = 'application/modules/sitedoctor/assets/css/component.css';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$data = preg_replace("#font-family:.*?;#si", "", $data);
echo "<style>".$data."</style>";


$path = 'application/modules/sitedoctor/assets/css/custom.css';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$data = preg_replace("#font-family:.*?;#si", "", $data);
echo "<style>".$data."</style>";
?>


