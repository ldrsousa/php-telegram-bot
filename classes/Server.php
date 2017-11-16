<?php

class Server extends Bot
{
	public function __construct($conf, $chat_id)
	{
		parent::__construct($conf, $chat_id);
	}

	private function uptime()
	{
		return "Server uptime:". exec('uptime');
	}

	private function uname()
	{
		return exec('uname -a');
	}
}
