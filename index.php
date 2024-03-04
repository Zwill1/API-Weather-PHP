<?php
$config = parse_ini_file('config.ini', true);

// Accessing settings
$API = $config['connection']['api'];
?>


<?php 

$weather = '';
$error = '';

if(array_key_exists('submit', $_GET)){
  //check if the input is empty
  if(!$_GET['city']) {
    $error = "Sorry, Your input field is empty";
  }
  if($_GET['city']){
    // Use @ to suppress warnings
    $apiData = @file_get_contents("https://api.openweathermap.org/data/2.5/weather?q=".$_GET['city']."&appid=".$API);

    // echo "$apiData";
    $weatherArray = json_decode($apiData, true);

    // if($weatherArray['cod'] == 200)
    if($weatherArray){
      // Converting temp C = K - 273.15
      $tempCalcCelsius = $weatherArray['main']['temp'] - 273;
      // print_r($tempCalcCelsius);
      $tempCelsius = number_format($tempCalcCelsius);
      // print_r($weatherArray);
      $weather = "<b>".$weatherArray['name'].", ".$weatherArray['sys']['country']. " : " .$tempCelsius."&deg;C </b> <br />";
      $weather .= "<b>Weather Condition: </b>".$weatherArray['weather']['0']['description']."<br />";
      $weather .= "<b>Wind Speed: </b>".$weatherArray['wind']['speed']." meter/sec <br />";
      date_default_timezone_set('MST');
      $sunrise = $weatherArray['sys']['sunrise']; 
      $weather .= "<b>Sunrise: </b>".date("g:i a", $sunrise)."<br />";
      $weather .= "<b>Current Time: </b>".date("F j, Y, g:i a")."<br />";
    } else {
      $error = "Couldnt find. Please enter a valid city.";
    }
  }
}

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
      <h1 class="text-center">Basic Weather API Test</h1>
      <form action="" method="GET">
        <div class="mb-3">
          <label for="city" class="form-label">Enter your city name:</label>
          <input type="text" name="city" class="form-control" id="city">
        </div>
        <button type="submit" name="submit" class="btn btn-success">Submit</button>
        <div>
            <?php 

              if($weather){
                echo '<div class="alert alert-success mt-3" role="alert">
                ' . $weather . '
                </div>';
              }

              if($error){
                echo '<div class="alert alert-danger mt-3" role="alert">
                ' . $error . '
                </div>';
              }
            ?>
        </div>
      </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>