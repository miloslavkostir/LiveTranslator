<?php

namespace LiveTranslator\DI;

class Extension extends \Nette\DI\CompilerExtension
{

	private $defaults = array(
		'layout' => 'vertical',
		'height' => 465
	);


	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaults);

		if (!isset($config['defaultLang'])){
			throw new \Nette\InvalidStateException("Config section for $this->name must define defaultLang parameter.");
		}

		$builder = $this->getContainerBuilder();
		$translator = $builder->addDefinition($this->prefix('translator'));
		$translator->setClass('LiveTranslator\Translator', array('@LiveTranslator\ITranslatorStorage', '@session'));
		$translator->addSetup('setDefaultLang', array($config['defaultLang']));
		//$translator->addSetup('LiveTranslator\Panel::register', array('@application', '@self', '@session', '@httpRequest', $config['layout'], $config['height']));
	}
}
