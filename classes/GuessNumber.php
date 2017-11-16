<?php

class GuessNumber extends Bot
{
	private $filename = 'guessnumber/number.txt';

	private $game;

	public function __construct($conf, $chat_id)
	{
		parent::__construct($conf, $chat_id);

		$this->game = new stdClass();
	}

	public function start()
	{
		$this->game->lives = 5;
		$this->game->number = mt_rand(1, 50);

		if (!is_dir('guessnumber')) {
			mkdir('guessnumber');
		}

		file_put_contents($this->filename, serialize($this->game));

		return 'Game started!' . chr(10) . 'Lives: ' . $this->game->lives . chr(10) . 'Guess a number between 1 and 50 with "/gn <number>"';
	}

	public function stop()
	{
		if (is_file($this->filename)) {
			unlink($this->filename);
			return 'Game is stopped! Come back later...';
		} else {
			return 'Game is not started!';
		}
	}

	public function guess($number)
	{
		if (!is_file($this->filename)) {
			return 'Game is not started!';
		} else {
			$this->game = unserialize(file_get_contents($this->filename));
		}

		if ($number === $this->game->number) {
			return $this->end();
		}

		if ($this->game->lives - 1 > 0) {
			$this->game->lives--;
		} else {
			return $this->end();
		}

		$answer = 'Lives: ' . $this->game->lives . chr(10);

		if ($number < $this->game->number) {
			$answer .= 'Your guess is lower than the number. Try again!';
		} else {
			$answer .= 'Your guess is greater than the number. Try again!';
		}

		file_put_contents($this->filename, serialize($this->game));

		return $answer;
	}

	private function end()
	{
		if (--$this->game->lives === 0) {
			$answer = 'Game over... You loose, the number was ' . $this->game->number . '!';
		} else {
			$answer = 'RIGHT!!! You win the game!';
		}

		if (is_file($this->filename)) {
			unlink($this->filename);
		}

		return $answer;
	}
}