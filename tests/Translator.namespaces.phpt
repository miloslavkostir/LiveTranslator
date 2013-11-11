<?php

use Tester\Assert;
$container = require __DIR__ . '/bootstrap.php';

require __DIR__.'/storage/namespace.php';

use \LiveTranslator\Translator as Tr;
$trans = new Tr(new NamespaceStorage, $container->session);

$trans->setDefaultLang('en')
	->setCurrentLang('cz')
	->setNamespace('first');

Assert::equal('Ahoj světe.', $trans->translate('Hello world.'));
$trans->translate('new string');

$trans->setNamespace('second');
$all = $trans->getAllTranslations();
Assert::equal(0, count($all[Tr::NEW_STRINGS]));

Assert::equal('Hello world.', $trans->translate('Hello world.'));
Assert::equal('Jmenuji se George.', $trans->translate('My name is %s.', 'George'));
