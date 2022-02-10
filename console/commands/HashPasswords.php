<?php
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HashPasswords extends Command
{
	protected $commandName = 'passwords:hash';
	protected $commandDescription = "Hash Passwords";

	protected function configure()
	{
		$this
				->setName($this->commandName)
				->setDescription($this->commandDescription);
	}
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$users = User::all();
		foreach ($users as $user){
			if($user->user_password != ""){
				$user->update(['user_password'=>password_hash($user->user_password, PASSWORD_DEFAULT)]);
			}
		}
		return 0;
	}
}
