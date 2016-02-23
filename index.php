<?php
include_once('includes/settings.php');
include_once('includes/zululog.php');

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Aerodromes</title>
    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 100%;
      }
      #floating-panel {
        position: absolute;
        top: 10px;
        left: 25%;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
        text-align: center;
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
      }
      #floating-panel {
        background-color: #fff;
        border: 1px solid #999;
        left: 25%;
        padding: 5px;
        position: absolute;
        top: 10px;
        z-index: 5;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <script>
    var map, heatmap;

function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {
    zoom: 6,
    center: {lat: -32, lng: 141},
    mapTypeId: google.maps.MapTypeId.SATELLITE
  });

  heatmap = new google.maps.visualization.HeatmapLayer({
    data: getPoints(),
    map: map
  });
  heatmap.set('radius', 20);
}
function getPoints() {
  return [
<?php
      $ADs = GetAirportCounts();
      foreach (array_keys($ADs) as $ad) {
        $adLL = GetAdLatLong($ad);
        print_r("      new google.maps.LatLng(".$adLL."),\n");
      }
    ?>
  ];
}

    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo($_SESSION['gMapsApiKey']);?>&signed_in=true&libraries=visualization&callback=initMap">
    </script>
  </body>
</html>

