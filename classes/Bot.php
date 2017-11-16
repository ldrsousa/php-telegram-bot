<?php

class Bot
{
	private $api_url = '';
	private $only_trusted = true;
	private $trusted = array();
	private $chat_id = '';

	public function __construct($conf, $chat_id)
	{
		$this->api_url = 'https://api.telegram.org/bot' . $conf['bot_token'];
		$this->only_trusted = $conf['only_trusted'];
		$this->trusted = $conf['trusted'];
		$this->chat_id = $chat_id;
	}

	public function isTrusted()
	{
		if (!$this->only_trusted) {
			return true;
		}

		if (in_array($this->chat_id, $this->trusted)) {
			return true;
		}

		return false;
	}

	private function log($message)
	{
		error_log(date("Y-m-d H:i:s") . " - " . $message . "\n", 3, 'log.log');
	}

	public function execute($command, $arg = '')
	{
		if ($command === 'unauthorized') {
			$message = "You are not authorized to use commands in this bot!";
		} else if ($command === 'unknown') {
			$message = "Unknown command, try /help to see a list of commands";
		} else {
			if ($command === 'guess') {
				$message = $this->{$command}($arg);
			} else if ($arg !== '') {
				$message = $this->{$arg}();
			} else {
				$message = $this->{$command}();
			}
		}

		$this->send($message);
	}

	private function send($message)
	{
		if (strlen($message) > 0) {
			$send = $this->api_url . "/sendmessage?chat_id=" . $this->chat_id . "&text=" . urlencode($message);
			file_get_contents($send);
			return true;
		}

		return false;
	}

	private function help()
	{
		$message = "/server uptime" . chr(10) . "  - Retrieves the uptime of the server" . chr(10) . chr(10);
		$message .= "/server uname" . chr(10) . "  - Retrieves the server name, build and kernel" . chr(10) . chr(10);
		$message .= "/games" . chr(10) . "  - Retrieves the games available in this bot";

		return $message;
	}

	private function games()
	{
		$message = "GuessNumber>" . chr(10);
		$message .= "Game to guess a number between 1 and 50" . chr(10) . chr(10);
		$message .= "/guessnumber <command> ou /gn <command>" . chr(10);
		$message .= "  start - Start the game" . chr(10);
		$message .= "  stop  - Stop the game" . chr(10);
		$message .= "  <number> - Guess a number";

		return $message;
	}
}
