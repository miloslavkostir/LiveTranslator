<?php

namespace LiveTranslator\Storage;


class NetteDatabase implements \LiveTranslator\ITranslatorStorage
{

	/** @var \Nette\Database\Connection */
	private $db;

	/** @var string */
	private $defaultTable;

	/** @var string */
	private $translationTable;



	/**
	 * @param string $defaultTableName name of table with original texts
	 * @param string $translationTableName name of table with translated texts
	 * @param \Nette\Database\Connection $db
	 * @param \Nette\Database\Context|NULL $context
	 */
	public function __construct($defaultTableName, $translationTableName, \Nette\Database\Connection $db, \Nette\Database\Context $context = NULL)
	{
		$this->db = $context ?: $db; // Context is part of newer Nette version
		if ($defaultTableName[0] !== '`') {
			$defaultTableName = "`{$defaultTableName}`";
		}
		if ($translationTableName[0] !== '`') {
			$translationTableName = "`{$translationTableName}`";
		}
		$this->defaultTable = $defaultTableName;
		$this->translationTable = $translationTableName;
	}



	public function getTranslation($original, $lang, $variant = 0, $namespace = NULL)
	{
		$arg = array();

		$arg[0] = "SELECT t.`translation` FROM {$this->defaultTable} d
			JOIN {$this->translationTable} t ON d.`id` = t.`text_id`
			WHERE ";

		if ($namespace){
			$arg[0] .= 'd.`ns` = ? AND ';
			$arg[] = $namespace;
		}

		$arg[0] .= 'BINARY d.`text` = ? AND t.`lang` = ? AND t.`variant` <= ? ORDER BY t.`variant` DESC';
		$arg[] = $original;
		$arg[] = $lang;
		$arg[] = $variant;

		$translation = $this->fetchField($arg);

		return $translation ?: NULL;
	}



	public function getAllTranslations($lang, $namespace = NULL)
	{
		$arg = array();

		$arg[0] = "SELECT d.`text`, t.`variant`, t.`translation` FROM {$this->defaultTable} d
			JOIN {$this->translationTable} t ON d.`id` = t.`text_id`
			WHERE ";

		if ($namespace){
			$arg[0] .= 'd.`ns` = ? AND ';
			$arg[] = $namespace;
		}

		$arg[0] .= "t.`lang` = ?";
		$arg[] = $lang;

		$translations = call_user_func_array(array($this->db, 'fetchAll'), $arg);

		$output = array();
		foreach ($translations as $translation){
			if (!isset($output[$translation->text])){
				$output[$translation->text] = array();
			}
			$output[$translation->text][$translation->variant] = $translation->translation;
		}
		return $output;
	}



	public function setTranslation($original, $translated, $lang, $variant = 0, $namespace = NULL)
	{
		$arg = array();

		$arg[0] = "SELECT `id` FROM {$this->defaultTable} WHERE ";

		if ($namespace){
			$arg[0] .= '`ns` = ? AND ';
			$arg[] = $namespace;
		}

		$arg[0] .= "BINARY `text` = ?";
		$arg[] = $original;

		$textId = $this->fetchField($arg);

		if ($textId){
			$id = $this->fetchField(array("
				SELECT `id` FROM {$this->translationTable}
				WHERE `text_id` = ? AND `lang` = ? AND `variant` = ?", $textId, $lang, $variant
			));
		}

		if ($textId && $id){
			$this->db->query("UPDATE {$this->translationTable} SET translation = ? WHERE id = ?", $translated, $id);
		}
		else {
			if (!$textId){
				$data = array('text' => $original);
				if ($namespace) $data['ns'] = $namespace;
				$this->db->query("INSERT INTO {$this->defaultTable} ?", $data);
				$textId = $this->db->fetch("SELECT LAST_INSERT_ID() id")->id;
			}

			$this->db->query("INSERT INTO {$this->translationTable} ?", array(
				'text_id' => $textId,
				'lang' => $lang,
				'variant' => $variant,
				'translation' => $translated,
			));
		}
	}



	public function removeTranslation($original, $lang, $namespace = NULL)
	{
		$arg = array();

		$arg[0] = "SELECT d.`id` FROM {$this->defaultTable} d
			JOIN {$this->translationTable} t ON d.`id` = t.`text_id`
			WHERE ";

		if ($namespace){
			$arg[0] .= 'd.`ns` = ? AND ';
			$arg[] = $namespace;
		}

		$arg[0] .= "d.`text` = ? AND t.`lang` = ?";
		$arg[] = $original;
		$arg[] = $lang;

		$id = $this->fetchField($arg);

		if ($id){
			$this->db->query("DELETE FROM {$this->translationTable} WHERE text_id = ?", $id);
			$this->db->query("DELETE FROM {$this->defaultTable} WHERE id = ?", $id);
		}
	}



	private function fetchField(array $args)
	{
		$databaseMethodName = $this->db instanceof \Nette\Database\Context ? 'fetchField' : 'fetchColumn';
		return call_user_func_array(array($this->db, $databaseMethodName), $args);
	}

}
