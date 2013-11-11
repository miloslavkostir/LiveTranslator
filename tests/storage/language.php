<?php

class LanguageStorage implements \LiveTranslator\ITranslatorStorage
{
	private $translations = array(
		'cz' => array(
			'Hello world.' => 'Ahoj světe.',
		),
		'de' => array(
			'Hello world.' => 'Hallo Welt.',
		),
	);

	function getTranslation($original, $lang, $v = 0, $n = NULL)
	{
		if (!isset($this->translations[$lang][$original])) return NULL;
		return $this->translations[$lang][$original];
	}

	function getAllTranslations($lang, $n = NULL)
	{
		return $this->translations[$lang];
	}

	function setTranslation($o, $t, $l, $v = 0, $n = NULL)
	{}

	function removeTranslation($o, $l, $n = NULL)
	{}
}
