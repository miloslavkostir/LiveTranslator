<?php

require __DIR__.'/bootstrap.service.php';

use Tester\Assert;
require __DIR__.'/storage/dummy.php';

$configurator = new Nette\Config\Configurator;
$configurator->setTempDirectory(__DIR__ . '/temp');
$configurator->addConfig(__DIR__.'/config/setup.neon');
$container = $configurator->createContainer();

/** @var $trans LiveTranslator\Translator */
$trans = $container->translator;


Assert::equal(array('en', 'cz', 'de'), $trans->getAvailableLanguages());
Assert::equal('front', $trans->getNamespace());
