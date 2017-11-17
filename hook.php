<?php
/**
 * PHP Telegram Bot
 *
 * A simple code to create a bot to telegram application
 *
 * Author: waterblue
 * Date: 2017/11/16
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
$commands = array(
	// General Commands
	'help',
	'games',

	// Server Commands
	'server',

	// Games
	'guessnumber',
	'gn', // alias for guessnumber
);

$arguments = array(
	// Server
	'server'=>array(
		'uptime',
		'uname',
	),

	// Games
	'guessnumber'=>array(
		'start',
		'stop',
	),
	'gn'=>array( // alias for guessnumber
		'start',
		'stop',
	),
);

$str = explode(' ', trim($message));

$command = ltrim($str[0], '/');
$arg = isset($str[1]) ? $str[1] : '';

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
	$hook->execute('unauthorized');
	die();
}

if (!in_array($command, $commands)) {
	$hook->execute('unknown');
}

else {
	if (isset($arguments[$command])) {

		foreach ($arguments[$command] as $a) {
			if ($a === $arg) {
				$hook->execute($command, $arg);
				die();
			}
		}

		if (($command === 'guessnumber' || $command === 'gn') && intval($arg) > 0) {
			$hook->execute('guess', intval($arg));
			die();
		}

		$hook->execute('unknown');
	}

	else {
		$hook->execute($command);
	}
}
