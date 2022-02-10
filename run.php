<?php
if(php_sapi_name() != 'cli'){
	header('HTTP/1.1 404 Not Found');
}
require __DIR__ . '/bootstrap.php';
use Symfony\Component\Console\Application;

$application = new Application();

# add our commands
$application->add(new MigrateCommand());
$application->add(new HashPasswords());
$application->add(new RenewalEmails());

$application->run();
