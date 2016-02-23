<?php
# import the CSV

$row = 1;
$logFile = "data/log.csv";

$_SESSION['logEntries'] = array();

if (($handle = fopen($logFile, "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    if ($data[0] === "Date"){
      // header row, skip it
    } else {
      $logEntry = array(
        "Date"=>$data[0],
        "Model"=>$data[1],
        "Aircraft ID"=>$data[2],
        "Route"=>$data[3],
        "User ID"=>$data[4],
        "T/O"=>$data[5],
        "Day Ldgs"=>$data[6],
        "Ngt Ldgs"=>$data[7],
        "IAP"=>$data[8],
        "SE"=>$data[9],
        "ME"=>$data[10],
        "Complex"=>$data[11],
        "Turbine"=>$data[12],
        "Rotorcraft"=>$data[13],
        "Flt Sim"=>$data[14],
        "X/C"=>$data[15],
        "IMC"=>$data[16],
        "Sim Inst"=>$data[17],
        "Flt Time"=>$data[18],
        "Night"=>$data[19],
        "PIC"=>$data[20],
        "SIC"=>$data[21],
        "Dual Recd"=>$data[22],
        "Dual Given"=>$data[23],
        "Remarks"=>$data[24],
      );
      array_push($_SESSION['logEntries'], $logEntry);
    }
  }
  fclose($handle);

}

function GetAirportCounts() {

  $results = array();
  // get all route positions into a string
  foreach ($_SESSION['logEntries'] as $flight) {
    $routePositions .= $flight["Route"]." ";
  }

  $sortedRoutePositions = array_count_values(str_word_count($routePositions, 1));
  // discard anything that's not an aerodrome (4 letters)
  foreach (array_keys($sortedRoutePositions) as $sortedRoutePosition) {
    if (strlen($sortedRoutePosition) == 4) {
      // location is an AD, add it to the array
      $results[$sortedRoutePosition] = $sortedRoutePositions[$sortedRoutePosition];
    } else {
      // location is not an AD, probably, so ignore it.
    }
  }

  return $results;

}


function GetAdLatLong($ad) {
  # import the CSV

  $dataFile = "data/ads.csv";
  $result = "0, 0";

  if (($handle = fopen($dataFile, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
      if ($data[0] == $ad) {
        $result = $data[2].", ".$data[3];
      }
    }
  fclose($handle);
  }
  return $result;
}


 function strendswith($haystack, $needle) {
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
  }



  // GetAerodromes returns an array of ADs visited by a particular flight.
  // It uses the Remarks section to evaluate anything not stored in the Zululog flight plan
  // as Zululog doesn't have all ALAs.
  function GetAerodromes($flight) {
    if (strlen($flight['Route']) == 4) {
      // if the route is only one four character string, it was probably circuits or a local flight
      return array($flight['Route']);
    } else {
      $ads = array();
      // add the first element to the list
      array_push($ads,explode(" ", $flight['Route'])[0]);
      // add the last element to the list
      array_push($ads,end(explode(" ", $flight['Route'])));
      foreach (explode(" ", $flight['Remarks']) as $waypoint) {
        // Split the route into bits
        if (strendswith($waypoint,'/')) {
          // If the waypoint ends with one or more slashes, it was a landing
          array_push($ads,str_replace('/','',$waypoint));
        }
      }
      return array_unique($ads);
    }
  }

  function GetAllAerodromes($logEntries) {
    $ads = array();
    foreach ($logEntries as $flight) {
      foreach (GetAerodromes($flight) as $ad) {
        array_push($ads,$ad);
      }
    }
    return array_unique($ads);
  }
?>

