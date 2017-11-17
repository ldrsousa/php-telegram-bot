<?php

class Server extends Bot
{
	public function __construct($conf, $chat_id)
	{
		parent::__construct($conf, $chat_id);
	}

	public function uptime()
	{
		return $this->send("Server uptime:". exec('uptime'));
	}

	public function uname()
	{
		return $this->send(exec('uname -a'));
	}

	public function calc($args)
	{
		$calc = [];

		foreach ($args as $a) {
			if ($a === '*' || $a === 'x') {
				$calc[] = '\\*';
			} else {
				$calc[] = $a;
			}
		}

		return $this->send(exec("expr " . implode(' ', $calc)));
	}
}
