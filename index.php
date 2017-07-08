<html>
<head>

  <link rel="stylesheet" href="dist/leaflet/leaflet.css"/>
  <script src="dist/leaflet/leaflet.js"></script>

  <style>
  #map {
    min-height:100%;
    min-width:100%;
  }
  </style>

</head>

<?php require "server.php";
      $squad_pubg = new map($db_servername,$db_username,$db_password,$db_name);
      $squad_pubg->read_poi(1);
      print_r($squad_pubg->db->log);
?>

<body>

  <div id="map"></div>

  <script>
    var map = L.map('map', {
      minZoom: 1,
      maxZoom: 4,
      center: [0, 0],
      zoom: 1,
      crs: L.CRS.Simple
    });

    // dimensions of the image
    var w = 5184,
        h = 5184,
        url = 'images/map-base.jpg';

    // calculate the edges of the image, in coordinate space
    var southWest = map.unproject([0, h], map.getMaxZoom()-1);
    var northEast = map.unproject([w, 0], map.getMaxZoom()-1);
    var bounds = new L.LatLngBounds(southWest, northEast);

    // add the image overlay,
    // so that it covers the entire map
    L.imageOverlay(url, bounds).addTo(map);

    // tell leaflet that the map is exactly as big as the image
    map.setMaxBounds(bounds);

    /* approx 80 units per tile */
    var marker = L.marker([-215, 115]).addTo(map);

  </script>

</body>
</html>
