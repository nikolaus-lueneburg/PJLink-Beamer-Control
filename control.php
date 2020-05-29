<?php
# Path to config file
include "config.php";

$response = array();
$pwtoken = "";

# Establish TCP connection to beamer
$url = 'tcp://' . $beamerIP . ':' . $beamerPort;

$client = stream_socket_client($url, $errorCode, $errorMessage);
stream_set_timeout($client, 2);

if ($client === false) {
    die('Device not responding');
}

$result = stream_get_line($client, 1024, "\r");

if (substr($result, 0, 8) == 'PJLINK 1') {
    $pwtoken = substr($result, 9, 8);
}

# Get command
if (!empty($_GET["mute"])) {
	switch ($_GET["mute"]) {
		case "on":
			$command = "%1AVMT 31";
		break;
		case "off":
			$command = "%1AVMT 30";
		break;
		default:
			die('ERROR: Please use "on" or "off" as value');
		break;
	}
}

if (!empty($_GET["power"])) {
	switch ($_GET["power"]) {
		case "on":
			$command = "%1POWR 1";
		break;
		case "off":
			$command = "%1POWR 0";
		break;
		default:
			die('ERROR: Please use "on" or "off" as value');
		break;
	}
}

if (!empty($_GET["input"])) {
	switch ($_GET["input"]) {
		case "1":
			$command = "%1INPT 31";
		break;
		case "2":
			$command = "%1INPT 32";
		break;
		case "get":
			$command = "%1INPT ?";
		break;		
		default:
			die('ERROR: Not defined value');
		break;
	}
}

# Encrypt command and send it to beamer
if (!empty($command)) {
	$command = md5($pwtoken . $beamerPassword) . $command;

	fwrite($client, $command . "\r");
	$result = stream_get_line($client, 1024, "\r");

	$resultCode = substr($result, strpos($result, '=') + 1);

	if (in_array($resultCode, array_keys($resultMessages))) {
		die($resultCode . ': ' . $resultMessages[$resultCode]);
	}
	echo $result;
}

# Status request
if ($_GET["status"] == "all") {
	foreach ($StatusCommands as $key => $value) {
		$command = md5($pwtoken . $beamerPassword) . $value;

		fwrite($client, $command . "\r");
		$result = stream_get_line($client, 1024, "\r");

		$resultCode = substr($result, strpos($result, '=') + 1);

		if (in_array($resultCode, array_keys($resultMessages))) {
			#die($resultCode . ': ' . $resultMessages[$resultCode]);
			continue;
		}

		$value = substr($result, strpos($result, "=") + 1);
		
		if($key == "lamp") {
			list ($value, $status) = explode(' ', $value);
		}
		
		$response[$key] = intval($value);
	}
}

# Close TCP connection
fclose($client);

//Output response array in JSON format
print_r(json_encode($response));

?>
