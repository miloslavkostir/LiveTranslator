<?php

use Tester\Assert;
$container = require __DIR__ . '/bootstrap.php';

function removeTranslations($dir) {
	$h = opendir($dir);
	while ($file = readdir($h)) {
		if (is_dir($file)) {
			continue;
		}
		unlink("$dir/$file");
	}
}

$dir = __DIR__.'/temp/File.storage';
if (!file_exists($dir)) {
	mkdir($dir);
}
removeTranslations($dir);
$fileStorage = new \LiveTranslator\Storage\File($dir);


/***************BASES***************/

Assert::null($fileStorage->getTranslation('apple', 'cz'));
$fileStorage->setTranslation('apple', 'jablko', 'cz');
Assert::equal('jablko', $fileStorage->getTranslation('apple', 'cz'));

Assert::null($fileStorage->getTranslation('My name is George.', 'cz'));
$fileStorage->setTranslation('My name is George.', 'Mé jméno je Jiří.', 'cz');
Assert::equal('Mé jméno je Jiří.', $fileStorage->getTranslation('My name is George.', 'cz'));
Assert::equal('jablko', $fileStorage->getTranslation('apple', 'cz'));

// calls destructor
$fileStorage = new \LiveTranslator\Storage\File($dir);
Assert::equal('Mé jméno je Jiří.', $fileStorage->getTranslation('My name is George.', 'cz'));
Assert::equal('jablko', $fileStorage->getTranslation('apple', 'cz'));

$allTranslations = $fileStorage->getAllTranslations('cz');
Assert::equal(2, count($allTranslations));
$expectations = array(
	array('apple', 'jablko'),
	array('My name is George.', 'Mé jméno je Jiří.'),
);
foreach ($allTranslations as $original => $translation) {
	$expect = array_shift($expectations);
	Assert::equal($expect[0], $original);
	if (is_array($translation)) {
		$translation = reset($translation);
	}
	Assert::equal($expect[1], $translation);
}


/***************PLURALS***************/

Assert::equal('jablko', $fileStorage->getTranslation('apple', 'cz', 1));
Assert::equal('jablko', $fileStorage->getTranslation('apple', 'cz', 2));
$fileStorage->setTranslation('apple', 'jablka', 'cz', 1);
Assert::equal('jablka', $fileStorage->getTranslation('apple', 'cz', 1));
$fileStorage = new \LiveTranslator\Storage\File($dir);
Assert::equal('jablka', $fileStorage->getTranslation('apple', 'cz', 1));

Assert::equal('jablka', $fileStorage->getTranslation('apple', 'cz', 2));
$fileStorage->setTranslation('apple', 'jablek', 'cz', 2);
Assert::equal('jablek', $fileStorage->getTranslation('apple', 'cz', 2));
$fileStorage = new \LiveTranslator\Storage\File($dir);
Assert::equal('jablek', $fileStorage->getTranslation('apple', 'cz', 2));

$fileStorage = new \LiveTranslator\Storage\File($dir);
$allTranslations = $fileStorage->getAllTranslations('cz');
Assert::equal(array('jablko', 'jablka', 'jablek'), $allTranslations['apple']);

// REMOVE test
$fileStorage->removeTranslation('apple', 'cz');
Assert::null($fileStorage->getTranslation('apple', 'cz'));
$fileStorage = new \LiveTranslator\Storage\File($dir);
Assert::null($fileStorage->getTranslation('apple', 'cz'));
$allTranslations = $fileStorage->getAllTranslations('cz');
Assert::equal(1, count($allTranslations));
$translation = reset($allTranslations);
if (is_array($translation)) {
	$translation = reset($translation);
}
$original = key($allTranslations);
Assert::equal('Mé jméno je Jiří.', $translation);
Assert::equal('My name is George.', $original);

unset($fileStorage);
removeTranslations($dir);
$fileStorage = new \LiveTranslator\Storage\File($dir);
$fileStorage->setTranslation('apple', 'jablka', 'cz', 1);
Assert::null($fileStorage->getTranslation('apple', 'cz', 0));
Assert::equal('jablka', $fileStorage->getTranslation('apple', 'cz', 1));
Assert::equal('jablka', $fileStorage->getTranslation('apple', 'cz', 2));
$fileStorage = new \LiveTranslator\Storage\File($dir);
Assert::null($fileStorage->getTranslation('apple', 'cz', 0));
Assert::equal('jablka', $fileStorage->getTranslation('apple', 'cz', 1));
Assert::equal('jablka', $fileStorage->getTranslation('apple', 'cz', 2));


/***************NAMESPACE***************/

unset($fileStorage);
removeTranslations($dir);
$fileStorage = new \LiveTranslator\Storage\File($dir);

$fileStorage->setTranslation('apple', 'jablko', 'cz', 0, 'A');
Assert::null($fileStorage->getTranslation('apple', 'cz', 0));
Assert::null($fileStorage->getTranslation('apple', 'cz', 0, 'B'));
Assert::equal('jablko', $fileStorage->getTranslation('apple', 'cz', 0, 'A'));
$fileStorage->setTranslation('apple', 'jablka', 'cz', 1, 'A');
Assert::equal('jablka', $fileStorage->getTranslation('apple', 'cz', 1, 'A'));

$fileStorage = new \LiveTranslator\Storage\File($dir);
Assert::equal(0, count($fileStorage->getAllTranslations('cz')));
Assert::equal(0, count($fileStorage->getAllTranslations('cz', 'B')));
$allTranslations = $fileStorage->getAllTranslations('cz', 'A');
Assert::equal(1, count($allTranslations));
$translation = reset($allTranslations);
$original = key($allTranslations);
Assert::equal(array('jablko', 'jablka'), $translation);
Assert::equal('apple', $original);

// REMOVE test
$fileStorage->removeTranslation('apple', 'cz', 'A');
Assert::null($fileStorage->getTranslation('apple', 'cz', 0, 'A'));
