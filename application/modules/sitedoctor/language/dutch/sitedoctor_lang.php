<?php 
//include("application/modules/sitedoctor/lang/required_index.php");
$lang=array();
$lang2d=array();
$langfinal=array();
$myDir=str_replace('language', 'lang', dirname(__FILE__));
$dirTree = array();
$di = new RecursiveDirectoryIterator($myDir,RecursiveDirectoryIterator::SKIP_DOTS);

$i=0;
foreach (new RecursiveIteratorIterator($di) as $filename) 
{

    $dir = str_replace($myDir, '', dirname($filename));

    $org_dir=str_replace("\\", "/", $dir);

    if($org_dir)
        $file_path = $org_dir. "/". basename($filename);
    else
        $file_path = basename($filename);

    $file_full_path=$myDir."/".$file_path;
    $file_size= filesize($file_full_path);
    $file_modification_time=filemtime($file_full_path);

    $dirTree[$i]['file'] = $file_full_path;
    $i++;
}

foreach ($dirTree as $key2 => $value2) 
{
    $langcurrent_file=isset($value2['file']) ? str_replace('\\', '/', $value2['file']) : ""; 
    if($langcurrent_file=="" || !is_file($langcurrent_file)) continue;
    $langcurrent_file_explode=explode('/',$langcurrent_file);
    $langfilename=array_pop($langcurrent_file_explode);
    $langpos=strpos($langfilename,'_lang.php');
    if($langpos!==false) // check if it is a lang file or not
    {       
       include($langcurrent_file);
       $lang2d[]=$lang;
    }
}

foreach ($lang2d as $key2 => $value2) 
{
  	foreach ($value2 as $key3 => $value3) 
  	{  	
  		// if(in_array($key3, $lang_index))
  		$langfinal[$key3]=$value3;
  	}
}  

$lang=array();
$lang=$langfinal;
?>