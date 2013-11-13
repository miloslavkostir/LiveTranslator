<?php

require __DIR__.'/bootstrap.service.php';

use Tester\Assert;
require __DIR__.'/storage/dummy.php';

$configurator = new Nette\Config\Configurator;
$configurator->setTempDirectory(__DIR__ . '/temp');
$configurator->addConfig(__DIR__.'/config/fail.noDefaultLang.neon');

Assert::exception(function() use($configurator){
	$configurator->createContainer();
}, 'Nette\DI\ServiceCreationException');
