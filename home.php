<!DOCTYPE HTML>
<html>

<body>
    <meta name="viewport" content="initial-scale=1.0">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!--  STYLE  -->

    <link href="css/style.css" rel="stylesheet" type="text/css">
    <!--  SCRIPTS  -->
    <script src="js/app.js"></script>

    <meta charset="utf-8">

    </head>

    <body>

        <div id="map"></div>


        <div id="contents"></div>

        <?php require_once(dirname(__FILE__)  . '/conf/config.php'); $gmaps_key= Conf::GMAPS_API; ?>
        <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $gmaps_key;?>&signed_in=true&libraries=drawing&callback=initMap" async defer></script>

    </body>

</html>