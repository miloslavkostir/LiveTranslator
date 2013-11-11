<?php

class DummyStorage implements \LiveTranslator\ITranslatorStorage
{
	function getTranslation($o, $l, $v = 0, $n = NULL)
	{}

	function getAllTranslations($l, $n = NULL)
	{}

	function setTranslation($o, $t, $l, $v = 0, $n = NULL)
	{}

	function removeTranslation($o, $l, $n = NULL)
	{}
}
