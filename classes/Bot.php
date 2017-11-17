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

	public function log($message)
	{
		error_log(date("Y-m-d H:i:s") . " - " . $message . "\n", 3, 'log.log');
	}

	public function send($message)
	{
		$text = trim($message);

		if (strlen(trim($text)) > 0) {
			$send = $this->api_url . "/sendmessage?chat_id=" . $this->chat_id . "&text=" . urlencode($text);
			file_get_contents($send);
			return true;
		}

		return false;
	}

	public function help()
	{
		$message = "/server uptime" . chr(10) . "  - Retrieves the uptime of the server" . chr(10) . chr(10);
		$message .= "/server uname" . chr(10) . "  - Retrieves the server name, build and kernel" . chr(10) . chr(10);
		$message .= "/server calc" . chr(10) . "  - Do simple math calc, like: 1 + 1" . chr(10) . chr(10);
		$message .= "/games" . chr(10) . "  - Retrieves the games available in this bot";

		return $this->send($message);
	}

	public function games()
	{
		$message = "GuessNumber" . chr(10);
		$message .= "Game to guess a number between 1 and 50" . chr(10) . chr(10);
		$message .= "/guessnumber <command> ou /gn <command>" . chr(10);
		$message .= "  start - Start the game" . chr(10);
		$message .= "  stop  - Stop the game" . chr(10);
		$message .= "  g <number> - Guess a number";

		return $this->send($message);
	}

	public function unauthorized()
	{
		return $this->send("You are not authorized to use commands in this bot!");
	}

	public function unknown()
	{
		return $this->send("Unknown command, try /help to see a list of commands");
	}
}
