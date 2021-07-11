<!-- Favicon -->
<link rel="shortcut icon" href="<?php echo base_url();?>assets/img/favicon.png"> 

<!-- Web Fonts -->
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700,300&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Raleway:700,400,300' rel='stylesheet' type='text/css'>

<!-- Bootstrap core CSS -->
<link href="<?php echo base_url();?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/modules/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<!-- Font Awesome CSS -->
<link href="<?php echo base_url()."assets/modules/fontawesome/css/all.min.css" ?>" rel="stylesheet" type="text/css" />

<?php 
$path = 'application/modules/sitedoctor/assets/css/component.css';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
// $data = preg_replace("#font-family:.*?;#si", "", $data);
echo "<style>".$data."</style>";

$path = 'application/modules/sitedoctor/assets/css/speedometer.css';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
// $data = preg_replace("#font-family:.*?;#si", "", $data);
echo "<style>".$data."</style>";

$path = 'application/modules/sitedoctor/assets/css/style.css';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
// $data = preg_replace("#font-family:.*?;#si", "", $data);
echo "<style>".$data."</style>";

$path = 'application/modules/sitedoctor/assets/css/custom.css';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
// $data = preg_replace("#font-family:.*?;#si", "", $data);
echo "<style>".$data."</style>";

// stisla styles
$path = 'assets/css/style.css';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
// $data = preg_replace("#font-family:.*?;#si", "", $data);
echo "<style>".$data."</style>";
?>

