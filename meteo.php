<?php
	include_once "include/util.inc.php";

	// On enregistre les cookies 
	$cookie_name = "derniere_consult";
    $cookie_value = $_GET['q'];
    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");

    // On stock ici le nom des villes visitées
    $consult = $_GET['q'] . "\n" ; 
	$file = fopen("statistiques/data/villes_consultees.txt", "a");
	fwrite($file , $consult); 
	fclose($file); 
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>Météo à <?php echo $_GET['q']; ?></title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" type="image/png" href="img/favicon.png" />
		<link rel="stylesheet" type="text/css" href="style/meteo.css">
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/weather-icons/2.0.9/css/weather-icons.min.css">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato"> 
		<script src="https://kit.fontawesome.com/931699da95.js" crossorigin="anonymous"></script>
		<style>
            .time {
                line-height: 25px;
            }
        </style>
	</head>

	<body>
		<header>
			<div class="m-logo">
				<a id="logo" href="index.php">
					<h1><i class="fas fa-meteor"></i> Météo²</h1>
				</a>
			</div>
			<div class="m-right">
				<p>by Stambouli Rayan & Sapa Randy</p>
			</div>
		</header>

		<div class="nomVille">
			<p><i class="fas fa-map-pin"></i> <?php echo $_GET['q']; ?></p>	
		</div>

		<div class="meteo">
			<div class="actuelle">
				<div class="DateHeure">
						<div style="font-size: 25px"><p><?php echo afficheDate('fr'); ?> | <span style="font-weight: bold; font-size: 30px"><?php echo heure('fr') ?></span></p></div>
						
				</div>
				<?php
					$city = $_GET['q'];
					$string = "http://api.openweathermap.org/data/2.5/weather?q=".$city."&lang=fr&units=metric&appid=ebcf5230b3446f334fe3fa2fd2d4ce24";
					
					$data = json_decode(file_get_contents($string),true);
						 
					$temp = $data['main']['temp'];
					$icon = $data['weather'][0]['icon'];
					$logo = "<div style='text-transform: capitalize;'><img src='http://openweathermap.org/img/w/".$icon.".png' class='weather-icon' alt='icon' >".$data['weather'][0]['description']."</div>";
							 
					$temperature =  "<p style='font-weight: bold; font-size: 30px;'>". round($temp) ."°C</p>";
					$humidity = "<p><i class='fas fa-tint'></i> Humidité : ".$data['main']['humidity']."%</p>";
					$wind = "<p><i class='fas fa-wind'></i> Vitesse du vent : ".$data['wind']['speed']." km/h</p>";
					$sunrise = "<p>Lever : ".date('H:i', $data['sys']['sunrise'])." | Coucher : ".date('H:i', $data['sys']['sunset'])."</p>";
				?>

				<div style="font-size: 25px; text-align: left; padding-left: 83px;">
				    <?php echo $temperature; ?>
				</div>
						
				<div style="font-size: 20px; text-align: left; padding-left: 70px;">
					<?php echo $logo; ?>
					<?php echo $humidity; ?>
					<?php echo $wind; ?>
					<?php echo $sunrise; ?>
				</div>
			</div>	
		

			<div class="cinqjours">
	            <?php
	                $string2="https://api.openweathermap.org/data/2.5/forecast/daily?q=". $city ."&lang=fr&units=metric&cnt=5&appid=aaddbefba9597425177be89dafe8c563";

	                $data = json_decode(file_get_contents($string2),true);

	                foreach($data['list'] as $day => $value) { 
	            ?>
	            <div class="days" style="text-align: center"> 
	                <div class="forecast"> 
	                        <?php
	                            $date_show = 'l';
	                            $date_m = 'F';
	                            $date_d = 'd';
	                            $today = strtotime('+1 day');
	                            $comingdays = strtotime('+'. $day .' day', $today);
	                              
	                            echo "<div class='fcheading'>";
	                            echo "<span style='font-size: 1.3em;'>". jours(date($date_show, $comingdays)) ."</span>";
	                            echo "<br/>";
	                            echo "" . date($date_d, $comingdays) . " " . mois(date($date_m, $comingdays)) ."";
	                            echo "</div>";
	                              
	                         	echo "<img src='http://openweathermap.org/img/w/".$value['weather'][0]['icon'].".png' class='weather-icon' alt='icon' >";

	                        ?>

	                        <?php
	                            echo "<p><span style='text-transform: capitalize; font-weight: bold;'>" . $value['weather'][0]['description'] . "</span></p>" ;
	                            echo "<p><span style='font-family: Lato; color: #B71C1C; font-size: 22px; font-weight: bold; '><strong>" . round($value['temp']['max']) . "°</strong></span></p>" ;
	                            echo "<p><span style='font-family: Lato; color: white; font-size: 19px; '>" . round($value['temp']['min']) . "°</span></p>" ;
	                        ?>
	                </div>
	            </div>
	            <?php } ?>
	        </div>

	        <div class="horaires">
	        	<?php
	        		$string3 = "https://api.weatherbit.io/v2.0/forecast/hourly?city=". str_to_noaccent($city) ."&lang=fr&key=f5aef7804ee2418abfc6e091261d6e5d&hours=9";
	        		$data = json_decode(file_get_contents($string3),true);

	        		// print_r($data);

	        		foreach($data['data'] as $key => $value) {
            	?>
	            <div class="hours" style="text-align: center"> 
	                <div class="forecast2"> 
	                    <?php
	                        $date = $value['timestamp_local'];
	                        $date = strtotime($date);
	                                     
	                        echo "<div class='fcheading'>";
	                        echo "<span style='font-size: 15px;'>". date('H:i', $date) ."</span>";
	                        echo "</div>";  
	                      
	                        $icon = $value['weather']['icon'];
	                        echo "<img src='https://www.weatherbit.io/static/img/icons/".$icon.".png' class='weather-icon2' alt='icon' >";
	                    ?>
	                    

	                    <?php
	                        echo "<p><span style='font-family: Lato; color: white; font-size: 18px; font-weight: bold;'>" . round($value['temp']) . "°</span></p>" ;
	                    ?>
	                </div>
	            </div>
	            <?php } ?>
	        </div>
        </div>
	</body>
</html>