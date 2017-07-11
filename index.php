<html>
<head>

  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mapster</title>

  <link rel="stylesheet" href="dist/foundation/css/foundation.css">
  <link rel="stylesheet" href="dist/foundation/icons/foundation-icons.css">
  <link rel="stylesheet" href="dist/leaflet/leaflet.css"/>
  <link rel="stylesheet" href="app.css" />

</head>

<body>

  <ul class="vertical medium-horizontal menu" style="background:black;">
  <li>
  <li><h5 style="position:relative;top:.2em; padding-left:.5em; color:white;"><i class="fi-map"></i> Mapster</h5></li>
  <li><a href="#0" style="color:white;"><i class="fi-list"></i> <span>One</span></a></li>
  <li><a href="#0" style="color:white;"><i class="fi-list"></i> <span>Two</span></a></li>
  <li><a href="#0" style="color:white;"><i class="fi-list"></i> <span>Three</span></a></li>
  <li><a href="#0" style="color:white;"><i class="fi-list"></i> <span>Four</span></a></li>
  </ul>

  <div id="map"></div>

  <script src="dist/foundation/js/vendor/jquery.js"></script>
  <script src="dist/foundation/js/vendor/what-input.js"></script>
  <script src="dist/foundation/js/vendor/foundation.js"></script>
  <script src="dist/leaflet/leaflet.js"></script>

  <script>

  $( document ).ready(function() {

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

    /* map info */

    var info = L.control();

    info.onAdd = function (map) {
        this._div = L.DomUtil.create('div', 'info'); // create a div with a class "info"
        this.update();
        return this._div;
    };

    info.update = function (props) {
        this._div.innerHTML = '<h5>Player Unknown\'s Battlegrounds</h5><p><b>Viewing map:</b> <?php echo $_GET['map']; ?></p>';
        if(props) {
          this._div.innerHTML += "<p>Map clicked at "+ props.latlng +"</p>";
        }
    };

    info.showNewForm = function (props) {
      console.log(props);
      this._div.innerHTML += "<p>Adding point at "+ props.latlng +"</p>";
    };

    info.addTo(map);

    /* display markers */

    $.ajax({
      method: "GET",
      url: "requests.php",
      data: { request: "readAll", map: "<?php echo $_GET['map']; ?>" }
    })
      .done(function( data ) {
        var json_data = $.parseJSON(data);
        $.each(json_data, function(key,value) {
          document["marker" + value.id] = L.marker([parseFloat(value.lat), parseFloat(value.long)]).addTo(map);
          document["marker" + value.id].bindPopup('<form><b>' + value.name + '</b>').openPopup();
        });
      });

      /* on click interactivity */

      var popup = L.popup();

      function onMapClick(e) {
          popup
              .setLatLng(e.latlng)
              .setContent("<a id='addNew' href='#'>Add Marker</a>")
              .openOn(map);
         info.update(e);
         console.log('map clicked');
         
         $('#addNew').click(function(e){
           info.showNewForm(e);
         });
      }

      function onMapAdd(coord) {
        console.log(coord);
        info.showNewForm(coord);
      }

      map.on('click', onMapClick);

      // requests.php?request=create&name=Test&type=1&lat="+ e.latlng.lat +"&long="+ e.latlng.lng +"&map=<?php echo $_GET['map']; ?>

    });

  </script>

</body>
</html>
