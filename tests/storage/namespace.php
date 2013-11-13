<?php

class NamespaceStorage implements \LiveTranslator\ITranslatorStorage
{
	private $translations = array(
		'first' => array(
			'Hello world.' => 'Ahoj světe.',
		),
		'second' => array(
			'My name is %s.' => 'Jmenuji se %s.',
		),
	);

	function getTranslation($original, $l, $v = 0, $ns = NULL)
	{
		if (!isset($this->translations[$ns][$original])) return NULL;
		return $this->translations[$ns][$original];
	}

	function getAllTranslations($l, $ns = NULL)
	{
		return $this->translations[$ns];
	}

	function setTranslation($o, $t, $l, $variant = 0, $n = NULL)
	{}

	function removeTranslation($o, $l, $n = NULL)
	{}
}
