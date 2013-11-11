<?php

use Tester\Assert;
$container = require __DIR__ . '/bootstrap.php';

require __DIR__.'/storage/language.php';

use \LiveTranslator\Translator as Tr;
$trans = new Tr(new LanguageStorage, $container->session);

$trans->setDefaultLang('en')
	->setCurrentLang('cz')
;

Assert::equal('Ahoj světe.', $trans->translate('Hello world.'));

$all = $trans->getAllTranslations();
Assert::equal(2, count($all));
unset($all[Tr::NEW_STRINGS]);
Assert::equal('Ahoj světe.', reset($all));

$trans->translate('Goodbye home.');
$trans->setCurrentLang('de');

Assert::equal('Hallo Welt.', $trans->translate('Hello world.'));

// checks if new string "Goodbye home." persists in session after switching language
$all = $trans->getAllTranslations();
Assert::equal(2, count($all));
Assert::equal(1, count($all[Tr::NEW_STRINGS]));
Assert::equal('Goodbye home.', key($all[Tr::NEW_STRINGS]));
unset($all[Tr::NEW_STRINGS]);
Assert::equal('Hallo Welt.', reset($all));
