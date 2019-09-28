<?php
// Beamer with PJLink support
$beamerIP = "192.168.1.123";
$beamerPort = 4352;
$beamerPassword = "1234"; // default = 1234

// Loxone Miniserver (Not in use!)
$loxoneIP = "192.168.1.10";
$loxoneUsername = "admin";
$loxonePassword = "password";

// All status commands
$StatusCommands = array(
    'lamp' => '%1LAMP ?',
    'power' => '%1POWR ?',
	'mute' => '%1AVMT ?'
);

$resultMessages = array(
	'OK' => 'Successful execution',
	'ERR1' => 'Undefined command',
	'ERR2' => 'Out of parameter',
	'ERR3' => 'Unavailable',
	'ERR4' => 'Projector/Display failure'
);

?>
