<?php

require_once 'config.php';

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?php echo $config['headline']; ?> | Timeliner</title>
    <meta charset="utf-8">
    <meta name="description" content="<?php echo $config['headline']; ?>">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <style>
      html, body {
       height:100%;
       padding: 0px;
       margin: 0px;
      }
      
      img {
        image-orientation: from-image;
      }
    </style>
    <!-- HTML5 shim, for IE6-8 support of HTML elements--><!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
  </head>
  <body>

      <div id="timeline-embed"></div>
      <script type="text/javascript">
        var timeline_config = <?php echo json_encode($config['timeline_config']); ?>
      </script>
      <script type="text/javascript" src="https://cdn.knightlab.com/libs/timeline/latest/js/storyjs-embed.js"></script>
  </body>
</html>
