<?php

require_once 'config.php';

$zipname = 'download.zip';
$zip = new ZipArchive;
$zip->open($zipname, ZipArchive::CREATE);
if ($handle = opendir('./' . $config['data_directory'])) {
  while (false !== ($entry = readdir($handle))) {
    if ($entry != "." && $entry != ".." && ! strstr($entry,'.php') && ! strstr($entry,'.zip') ) {
        $zip->addFile($entry);
    }
  }
  closedir($handle);
}

$zip->close();

header('Content-Type: application/zip');
header("Content-Disposition: attachment; filename='".$zipname."'");
header('Content-Length: ' . filesize($zipname));
header("Location: ".$zipname);

