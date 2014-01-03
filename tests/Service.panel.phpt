<?php

require __DIR__.'/bootstrap.php';

use Tester\Assert;
require __DIR__.'/storage/dummy.php';

$configurator = new Nette\Config\Configurator;
$configurator->setTempDirectory(__DIR__ . '/temp');
$configurator->addConfig(__DIR__.'/config/panel.neon');
$container = $configurator->createContainer();


Assert::type('LiveTranslator\Panel', $container->translatorPanel);
Assert::equal('horizontal', $container->translatorPanel->getLayout());
Assert::equal(500, $container->translatorPanel->getHeight());

if (\Nette\Framework::VERSION_ID >= 20100) {
	\Nette\Diagnostics\Debugger::getBar()->addPanel($container->translatorPanel);

} else {
	\Nette\Diagnostics\Debugger::$bar->addPanel($container->translatorPanel);
}
