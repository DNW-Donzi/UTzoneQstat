<?php

	// -------------------------------------------------------------------------
	// Datei: quakestat.php
	// Beschreibung: Anzeige von Spielservern mithilfe von quakestat (qstat).
	// -------------------------------------------------------------------------
	
	// Sonderzeichen und Farbcodes aus String löschen
	function delsc( $input ) {
		// Trackmaniafarbcodes löschen
		$input = preg_replace('/\$[wnismg]/', '', $input);
		$input = preg_replace('/\$[0-9a-zA-Z]{3}/', '', $input);
		// Sonderzeichen löschen
		$input = preg_replace('/[^\w\d\.\ ]/', '', $input);
		// Leerzeichen löschen
		$input = preg_replace('/\s\s+/', ' ', $input);
		$input = preg_replace('/^\s+/', '', $input);
		return ($input);
	}

	// Hilfetext
	$help='Beispiel: quakestat.php?game=GAME&serverip=IP&port=PORT&out=OUT&template=NR&delsc=TRUE';
	
	// Url Parameter auswerten
	if (isset($_GET['game']) && preg_match('/^[0-9a-zA-Z]{1,10}$/', $_GET['game'])) {
		$game = $_GET['game'];
	} else {
		exit('Fehler im Parameter GAME<br/>'.$help);
	}
	if (isset($_GET['serverip']) && preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/', $_GET['serverip'])) {
		$serverip = $_GET['serverip'];
	} else {
		exit('Fehler im Parameter SERVERIP<br/>'.$help);
	}
	if (isset($_GET['port']) && preg_match('/^[0-9]{2,5}$/', $_GET['port'])) {
		$port = $_GET['port'];
	} else {
		exit('Fehler im Parameter PORT<br/>'.$help);
	}
	if (isset($_GET['out'])) {
		$out = $_GET['out'];
	} else {
		$out = 'html';
	}
	if (isset($_GET['template']) && preg_match('/^[0-9]{2,2}$/', $_GET['template'])) {
		$template = $_GET['template'];
	} else {
		$template = '01';
	}
	if (isset($_GET['delsc']) && preg_match('/true|false/', $_GET['delsc'])) {
		$delsc = $_GET['delsc'];
	} else {
		$delsc = 'true';
	}
	
	// quakestat ausfuehren und Ausgabe in Variable query ablegen
	$query = shell_exec('quakestat -cfg qstat.cfg -Ts qstatTs.xml -' . $game . ' ' . $serverip . ':' . $port);
	
	if (isset($query)) {
		// Ausgabe von quakestat in Variablen ablegen
		$xml = simplexml_load_string($query);
		if ($delsc == 'true') {
			$name = delsc( $xml->name );
			$map = delsc( $xml->map );
		} else {
			$name = $xml->name;
			$map = $xml->map;
		}
		$hostname = $xml->hostname;
		$address = $xml->address;
		$status = $xml->status;
		$game = $xml->game;
		$gamestring = $xml->gamestring;
		$gametype = $xml->gametype;
		$numplayers = $xml->numplayers;
		$maxplayers = $xml->maxplayers;
		
		// Ausgabe formatieren
		switch ($out) {
			case "xml":
				echo $query;
			break;
			case "txt":
				echo 'Server: '.$name.', Hostname: '.$hostname.', Address: '.$address.', Status: '.$status.', Game: '.$game.', Gametype: '.$gametype.', Map: '.$map.', Player: '.$numplayers.'/'.$maxplayers;
			break;
			case "irc":
				echo 'Server: '.$name.', Hostname: '.$hostname.', Game: '.$game.', Gametype: '.$gametype.', Map: '.$map.', Player: '.$numplayers.'/'.$maxplayers;
			break;
			case "cms":
				echo $name.','.$hostname.','.$address.','.$status.','.$game.','.$gametype.','.$map.','.$numplayers.','.$maxplayers;
			break;
			case "json":
				header('Content-Type: text/javascript; charset=utf8');
				echo $_GET['callback'].'('.'{"Name":"'.$name.'","Hostname":"'.$hostname.'","Address":"'.$address.'","Status":"'.$status.'","Game":"'.$game.'","Gametype":"'.$gametype.'","Map":"'.$map.'","Numplayers":"'.$numplayers.'","Maxplayers":"'.$maxplayers.'"}'.')';
			break;
			case "svg":
				$svg = file_get_contents('svg'.$template.'.svg');
				if ($svg) {
					$search  = array('{SERVERNAME}', '{HOSTNAME}', '{ADDRESS}','{STATUS}', '{GAME}', '{GAMETYPE}', '{MAPNAME}', '{N}', '{M}');
					$replace = array($name, $hostname, $address, $status, $game, $gametype, $map, $numplayers, $maxplayers);
					$svgimg = str_replace($search, $replace, $svg);
					header("Content-Type: image/svg+xml");
					echo $svgimg;
				}
			break;
			case "png":
				$svg = file_get_contents('svg'.$template.'.svg');
				if ($svg) {
					$search  = array('{SERVERNAME}', '{HOSTNAME}', '{ADDRESS}','{STATUS}', '{GAME}', '{GAMETYPE}', '{MAPNAME}', '{N}', '{M}');
					$replace = array($name, $hostname, $address, $status, $game, $gametype, $map, $numplayers, $maxplayers);
					$svgimg = str_replace($search, $replace, $svg);
					$img = new Imagick();
					$img->setBackgroundColor(new ImagickPixel('transparent'));
					$img->readImageBlob($svgimg);
					$img->setImageFormat("png32");
					header('Content-type: image/png');
					echo $img;
				}
			break;
			default:
				echo '<table border="0" class="quakestat">'."\n";
				echo '<tr><th>Name:</th><th>'.$name.'</th></tr>'."\n";
				echo '<tr><td>Hostame:</td><td>'.$hostname.'</td></tr>'."\n";
				echo '<tr><td>Game:</td><td>'.$game.'</td></tr>'."\n";
				echo '<tr><td>Gametype:</td><td>'.$gametype.'</td></tr>'."\n";
				echo '<tr><td>Map:</td><td>'.$map.'</td></tr>'."\n";
				echo '<tr><td>Player:</td><td>'.$numplayers.'/'.$maxplayers.'</td></tr>'."\n";
				echo '</table>';
			break;
		}
		
	} else {
		exit('Fehler bei Ausfuehrung von quakestat! Eventuell nicht installiert?');
	}

?>
