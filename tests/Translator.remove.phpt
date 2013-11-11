<?php

use Tester\Assert;
$container = require __DIR__ . '/bootstrap.php';

require __DIR__.'/storage/simple.php';

use \LiveTranslator\Translator as Tr;
$trans = new Tr(new SimpleStorage, $container->session);

$trans->setDefaultLang('en')
	->setCurrentLang('cz')
;

$trans->setTranslation('Hello world.', FALSE);
Assert::equal('Hello world.', $trans->translate('Hello world.'));
$all = $trans->getAllTranslations();
Assert::equal(1, count($all[Tr::NEW_STRINGS]));

// should not throw anything
$trans->setTranslation('untranslated', FALSE);
