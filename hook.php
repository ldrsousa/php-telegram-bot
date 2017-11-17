<?php
/**
 * PHP Telegram Bot
 *
 * A simple code to create a bot to telegram application
 *
 * Author: waterblue
 * Date: 2017/11/17
 */

include_once "conf.php";

spl_autoload_register(function($class) {
	include_once 'classes/' . $class . '.php';
});

$content = file_get_contents('php://input');
$update = json_decode($content, true);
$chat_id = $update['message']['chat']['id'];
$message = $update['message']['text'];

// Available bot commands
$commands = [
	// General Commands
	'help',
	'games',

	// Server Commands
	'server',

	// Games
	'guessnumber',
	'gn', // alias for guessnumber
];

$arguments = [
	// Server
	'server'=>[
		'uptime',
		'uname',
		'calc',
	],

	// Games
	'guessnumber'=>[
		'start',
		'stop',
	],
	'gn'=>[ // alias for guessnumber
		'start',
		'stop',
		'guess',
		'g', // alias for guess
	],
];

$args = explode(' ', trim($message));

$command = ltrim(array_shift($args), '/');
$method = '';
if (isset($args[0]) && in_array($args[0], $arguments[$command])) {
	$method = array_shift($args);
}

switch ($command) {
	case 'server':
		$class = 'Server';
		break;
	case 'guessnumber':
	case 'gn':
		$class = 'GuessNumber';
		break;
	default:
		$class = 'Bot';
}

$hook = new $class($conf, $chat_id);

if (!$hook->isTrusted()) {
	$hook->unauthorized();
	die();
}

if (!in_array($command, $commands)) {
	$hook->unknown();
}

else {
	if (isset($arguments[$command]) && in_array($method, $arguments[$command])) {
		$hook->{$method}($args);
		die();
	} else if (in_array($command, $commands)) {
		$hook->{$command}($args);
	} else {
		$hook->unknown();
	}
}
