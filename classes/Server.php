<?php

class Server extends Bot
{
	public function __construct($conf, $chat_id)
	{
		parent::__construct($conf, $chat_id);
	}

	public function uptime()
	{
		return "Server uptime:". exec('uptime');
	}

	public function uname()
	{
		return exec('uname -a');
	}
}
