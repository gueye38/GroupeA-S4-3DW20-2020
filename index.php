<?php
	//~ Affiche les éventuelles erreurs
	//~ depuis https://blog.teamtreehouse.com/how-to-debug-in-php
	//~ À EFFACER UNE FOIS LE DEBUG TERMINÉ
	ini_set('display_errors', 'On');
	error_reporting(E_ALL | E_STRICT);

	//~ Ajout du moteur de templates Twig
	include 'inc.twig.php';

	//	Recupération du template de l'index
	$template_index = $twig->loadTemplate('index.tpl');

	//~ Le nombre de jours à vérifier
	$n_jours_previsions = 3;

	$ville = "Limoges"; 

	//~ Clé API
	//~ Si besoin, vous pouvez générer votre propre clé d'API gratuitement, en créant 
	//~ votre propre compte ici : https://home.openweathermap.org/users/sign_up
	$apikey = "10eb2d60d4f267c79acb4814e95bc7dc";

	$data_url = 'http://api.openweathermap.org/data/2.5/forecast/daily?APPID='.$apikey.'&q='.$ville.',fr&lang=fr&units=metric&cnt='.$n_jours_previsions;

	$data_contenu = file_get_contents($data_url);
		
	$_data_array = json_decode($data_contenu, true);

	// Récupérer la ville et les journées retournées
	$_ville = $_data_array['city'];
	$_journees_meteo = $_data_array['list'];

	//	Pour chacune des journées retournées on obtient l'icone correspondant
	for ($i = 0; $i < count($_journees_meteo); $i++) {
		$_meteo = getMeteoImage($_journees_meteo[$i]['weather'][0]['icon']);
		
		$_journees_meteo[$i]['meteo'] = $_meteo;
	}

	// On réalise un rendu de l'index
	echo $template_index->render(array(
		'_journees_meteo'	=> $_journees_meteo,
		'_ville' => $_ville,
		'n_jours_previsions' => $n_jours_previsions
	));

	function getMeteoImage($code){
		if(strpos($code, 'n')){
			return 'entypo-moon';
		}
		$_icones_meteo =array(
			'01d' => 'entypo-light-up',
			'02d' => 'entypo-light-up',
			'03d' => 'entypo-cloud',
			'04d' => 'entypo-cloud',
			'09d' => 'entypo-water', 
			'10d' => 'entypo-water',
			'11d' => 'entypo-flash',
			'13d' => 'entypo-star', 
			'50d' => 'entypo-air'
		);
// permet de vérifier si le code météo existe dans notre tableau.
		if(array_key_exists($code, $_icones_meteo)){
// retourne l'icone du météo si le code existe dans notre tableau. 
			return $_icones_meteo[$code];
		} else {
			return 'entypo-help';
		}
	}
?>
