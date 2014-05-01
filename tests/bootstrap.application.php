<?php

// todo mazat automaticky cache?

require __DIR__ . '/../vendor/autoload.php';

if (!class_exists('Tester\Assert')){
	echo "Install Nette Tester using `composer update --dev`\n";
	exit(1);
}

Tester\Environment::setup();

$configurator = new Nette\Configurator;
$configurator->setTempDirectory(__DIR__ . '/temp');
return $configurator->createContainer();
