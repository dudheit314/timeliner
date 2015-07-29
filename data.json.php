<?php

require_once 'config.php';

function utf8($s) {
    $encode = htmlentities($s, ENT_SUBSTITUTE, 'utf-8');
    return utf8_encode($encode);
}

// ensure directory existing
if ( ! file_exists($config['data_directory'])) {
    mkdir($config['data_directory'], 0777, true);
}

// start json generation
$dates = array();
foreach (glob($config['data_directory'] . '/*.*') as $file) {
    
    $file_name = basename($file);
    $file_ext = pathinfo($file, PATHINFO_EXTENSION);
    
    // allow JPG only
    if ( !in_array($file_ext, array('JPG', 'jpg'))) {
        continue;
    }

    // read exif information
    $exif = exif_read_data($file, 0, true);
    
    $data = new stdClass();
    
    // read the date from exif
    $data->date = isset($exif['EXIF']['DateTimeOriginal']) ? $exif['EXIF']['DateTimeOriginal'] : '';
    $data->date = date('Y,n,j' , strtotime($data->date));
    
    // read iptc information
    $info = array();                      
    getimagesize($file, $info);
    if(isset($info['APP13']))
    {
        $iptc = iptcparse($info['APP13']);
        $data->title = isset($iptc['2#005'][0]) ? $iptc['2#005'][0] : '';
        $data->desc = isset($iptc['2#120'][0]) ? $iptc['2#120'][0] : '';
    }
    else 
    {
        // fallback for EXIF
        $data->desc = isset($exif['IFD0']['Subject']) ? $exif['IFD0']['Subject'] : '';
        $data->title = isset($exif['IFD0']['Title']) ? $exif['IFD0']['Title'] : '';
    }
    
    // check for caption in (...)
    $match = array();
    preg_match('#\((.*?)\)#', $data->desc, $match);
    $data->caption = '';
    if (count($match) > 0) {
        $data->caption =  $match[1];
        $data->desc = str_replace($match[0], '', $data->desc);
    }

    // push data
    $dates[] = array(
      'startDate' => $data->date,
      'endDate' => $data->date,
      'headline' => utf8($data->title),
      'text' => '</p>'.utf8($data->desc).'</p>',
      'asset'=> array(
          'media' => $file,
          'credit' => '',
          'caption' => utf8($data->caption)
      )
    );

    $timeline = array(
        'headline' => utf8($config['headline']),
        'text' => '<p>' . utf8($config['text']) . '</p><br /><br /><a href="'. $config['data_directory'].'/zip.php">Download</a>',
        'type' => 'default',
        'date' => $dates
    );
    

}
    

?>

{
    "timeline": <?php echo json_encode($timeline); ?>
}
