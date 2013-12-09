<?php

use Tester\Assert;
$container = require __DIR__ . '/bootstrap.php';

require __DIR__.'/storage/namespace.php';

use \LiveTranslator\Translator as Tr;
$trans = new Tr('en', new NamespaceStorage, $container->session, $container->application);

$trans->setCurrentLang('cz')
	->setNamespace('first');

Assert::equal('Ahoj světe.', $trans->translate('Hello world.'));
$trans->translate('new string');

$trans->setNamespace('second');
$all = $trans->getAllStrings();
Assert::equal(1, count($all));
Assert::false(array_key_exists('new string', $all));

Assert::equal('Hello world.', $trans->translate('Hello world.'));
Assert::equal('Jmenuji se George.', $trans->translate('My name is %s.', 'George'));
