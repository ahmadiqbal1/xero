<?php 

$path = 'application/modules/sitedoctor/assets/plugins/ms-dropdown/js/jquery-1.9.0.min.js';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
// $data = preg_replace("#font-family:.*?;#si", "", $data);
echo "<script>".$data."</script>";

$path = 'application/modules/sitedoctor/assets/plugins/ms-dropdown/js/jquery.dd.js';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
// $data = preg_replace("#font-family:.*?;#si", "", $data);
echo "<script>".$data."</script>";

echo '<script>
    	var $j= jQuery.noConflict();
</script>';

$path = 'application/modules/sitedoctor/assets/plugins/jquery.min.js';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
// $data = preg_replace("#font-family:.*?;#si", "", $data);
echo "<script>".$data."</script>";

$path = 'application/modules/sitedoctor/assets/bootstrap/js/bootstrap.min.js';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
// $data = preg_replace("#font-family:.*?;#si", "", $data);
echo "<script>".$data."</script>";


$path = 'application/modules/sitedoctor/assets/js/speedometer.js';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
// $data = preg_replace("#font-family:.*?;#si", "", $data);
echo "<script>".$data."</script>";

$path = 'plugins/knob/jquery.knob.js';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
// $data = preg_replace("#font-family:.*?;#si", "", $data);
echo "<script>".$data."</script>";


?>



