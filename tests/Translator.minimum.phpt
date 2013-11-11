<?php

use Tester\Assert;
$container = require __DIR__ . '/bootstrap.php';

require __DIR__.'/storage/dummy.php';

$trans = new \LiveTranslator\Translator(new DummyStorage, $container->session);

$trans->setDefaultLang('en');

$trans->translate('hello');
