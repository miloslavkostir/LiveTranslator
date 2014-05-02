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

$dir = __DIR__.'/temp/File.storage.whitespace';
if (!file_exists($dir)) {
	mkdir($dir);
}
removeTranslations($dir);
$fileStorage = new \LiveTranslator\Storage\File($dir);

/*
$fileStorage->setTranslation('apple', "jablko\n", 'cz');
Assert::equal("jablko\n", $fileStorage->getTranslation('apple', 'cz'));
$fileStorage->setTranslation('My name is George.', 'Mé jméno je Jiří.', 'cz');
Assert::equal('Mé jméno je Jiří.', $fileStorage->getTranslation('My name is George.', 'cz'));
Assert::equal("jablko\n", $fileStorage->getTranslation('apple', 'cz'));
*/


$fileStorage->setTranslation('apple', "jablko\n", 'cz');
Assert::equal("jablko\n", $fileStorage->getTranslation('apple', 'cz'));

// calls destructor
$fileStorage = new \LiveTranslator\Storage\File($dir);
Assert::equal("jablko\n", $fileStorage->getTranslation('apple', 'cz'));
