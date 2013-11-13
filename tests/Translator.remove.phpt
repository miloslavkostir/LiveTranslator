<?php

use Tester\Assert;
$container = require __DIR__ . '/bootstrap.php';

require __DIR__.'/storage/simple.php';

use \LiveTranslator\Translator as Tr;
$trans = new Tr('en', new SimpleStorage, $container->session);

$trans->setCurrentLang('cz');

$trans->setTranslation('Hello world.', FALSE);
Assert::equal('Hello world.', $trans->translate('Hello world.'));
$all = $trans->getAllStrings();
Assert::true(array_key_exists('Hello world.', $all));

// should not throw anything
$trans->setTranslation('untranslated', FALSE);
