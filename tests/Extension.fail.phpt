<?php

require __DIR__.'/bootstrap.extension.php';

use Tester\Assert;
require __DIR__.'/storage/dummy.php';

$configurator = new Nette\Config\Configurator;
$configurator->setTempDirectory(__DIR__ . '/temp');
$configurator->onCompile[] = function ($c, Nette\Config\Compiler $compiler) {
	$compiler->addExtension('translator', new LiveTranslator\DI\Extension);
};
$configurator->addConfig(__DIR__.'/config/fail.neon');

Assert::exception(function() use($configurator){
	$configurator->createContainer();
}, 'Nette\InvalidStateException');
