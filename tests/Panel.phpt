<?php

use Tester\Assert;
$container = require __DIR__ . '/bootstrap.php';

require __DIR__.'/storage/simple.php';

$panel = new \LiveTranslator\Panel(
	new \LiveTranslator\Translator('en', new SimpleStorage, $container->session), $container->httpRequest
);

Assert::type('LiveTranslator\Translator', $panel->getTranslator());
$panel->setLayout('horizontal')
	->setHeight(500);

Assert::equal('horizontal', $panel->getLayout());
Assert::equal(500, $panel->getHeight());
Assert::exception(function() use($panel){
	$panel->setLayout('nothing');
}, 'Nette\InvalidArgumentException');

Assert::true(is_string($panel->getTab()));
Assert::true(is_string($panel->getPanel()));
