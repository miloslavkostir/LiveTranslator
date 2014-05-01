<?php

require __DIR__.'/bootstrap.php';

use Tester\Assert;
require __DIR__.'/storage/dummy.php';

$configurator = new Nette\Configurator;
$configurator->setTempDirectory(__DIR__ . '/temp');
$configurator->addConfig(__DIR__.'/config/panel.neon');
$container = $configurator->createContainer();


Assert::type('LiveTranslator\Panel', $container->getService('translatorPanel'));
Assert::equal('horizontal', $container->getService('translatorPanel')->getLayout());
Assert::equal(500, $container->getService('translatorPanel')->getHeight());

if (\Nette\Framework::VERSION_ID >= 20100) {
	\Nette\Diagnostics\Debugger::getBar()->addPanel($container->getService('translatorPanel'));

} else {
	\Nette\Diagnostics\Debugger::$bar->addPanel($container->getService('translatorPanel'));
}
