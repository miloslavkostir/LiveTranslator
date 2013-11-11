<?php

use Tester\Assert;
$container = require __DIR__ . '/bootstrap.php';

require __DIR__.'/storage/simple.php';

use \LiveTranslator\Translator as Tr;
$trans = new Tr(new SimpleStorage, $container->session);

$trans->setDefaultLang('en')
	->setCurrentLang('cz')
;

$all = $trans->getAllTranslations();

Assert::equal(7, count($all));
Assert::equal(0, count($all[Tr::NEW_STRINGS]));

$trans->translate('new string');
$all = $trans->getAllTranslations();

Assert::equal(1, count($all[Tr::NEW_STRINGS]));
Assert::equal('new string', key($all[Tr::NEW_STRINGS]));
