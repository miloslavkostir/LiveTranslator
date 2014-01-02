<?php

namespace LiveTranslator;

interface ITranslatorStorage
{

    /**
     * Return translated string.
     * For nonexistent variant return lower variant.
     * Return NULL if translation does not exist.
     * @param string $original
     * @param string $lang
     * @param int $variant
     * @param string $namespace
     * @return string|null
     */
    function getTranslation($original, $lang, $variant = 0, $namespace = NULL);


	/**
	 * Return all translations in all variants for given language.
	 * If there is only one variant nested array could be omitted (except if the only variant is not singular).
	 * Example of returned array: ['bike' => [0 => 'Fahrrad', 1 => 'Fahrräder'], 'Hello world.' => 'Hallo Welt.', ...]
	 * @param string $lang
	 * @param string $namespace
	 * @return array
	 */
	function getAllTranslations($lang, $namespace = NULL);


    /**
     * @param string $original
     * @param string $translated
     * @param string $lang
     * @param int $variant
     * @param string $namespace
     * @return void
     */
    function setTranslation($original, $translated, $lang, $variant = 0, $namespace = NULL);


	/**
	 * @param string $original
	 * @param string $lang
	 * @param string $namespace
	 * @return void
	 */
	function removeTranslation($original, $lang, $namespace = NULL);
}
