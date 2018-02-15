<?php

namespace goldencode\Helpers\Bitrix;

use Bitrix\Main\Loader;

class AppConfig {
	private $instance = null;
	private $options = [];
	private $values = [];
	private $appConfig = [];

	/**
	 * AppConfig constructor.
	 * @param array $options
	 * @throws \Bitrix\Main\LoaderException
	 */
	public function __construct(array $options = []) {
		if ($this->instance) return $this->instance;

		Loader::includeModule('iblock');

		$this->options = array_merge($this->options, [
			'iblockCode' => 'AppConfig',
			'configProps' => []
		], $options);

		$this->init();

		$this->instance = $this;
		return $this;
	}

	/**
	 * Init values array
	 * @throws \Bitrix\Main\LoaderException
	 */
	private function init() {
		$res = [];

		$this->appConfig = \CIBlockElement::GetList(
			['ACTIVE' => 'Y'],
			['IBLOCK_CODE' => $this->options['iblockCode']],
			false,
			false,
			array_map(function($key) {
				return 'PROPERTY_' . $key;
			}, $this->options['configProps'])
		)->Fetch();

		if (!$this->appConfig) {
			$this->create();
			return;
		}

		foreach ($this->options['configProps'] as $key) {
			$res[$key] = $this->appConfig['PROPERTY_' . $key . '_VALUE'];
		}

		$this->values = $res;
	}

	/**
	 * Create AppConfig
	 * @throws \Bitrix\Main\LoaderException
	 */
	private function create() {
		$iblockId = IblockUtility::getIblockIdByCode($this->options['iblockCode']);
		(new \CIBlockElement())->Add([
			'IBLOCK_ID' => $iblockId,
			'CREATED_BY' => 1,
			'MODIFIED_BY' => 1,
			'NAME' => 'Settings',
			'ACTIVE' => 'Y'
		]);
		$this->init();
	}

	/**
	 * Get config property
	 * @param string $prop property name
	 * @param string $default default value to return
	 * @param callable $transform function($value) that transforms value
	 * @return mixed|string
	 */
	public function get($prop, $default = '', callable $transform = null) {
		if (is_null($transform)) $transform = function($a) {return $a;};
		$val = $this->appConfig['PROPERTY_' . $prop . '_VALUE'];
		return call_user_func($transform, $val ? $val : $default);
	}

	/**
	 * Get AppConfig
	 * @return array
	 */
	public function getAppConfig()
	{
		return $this->appConfig;
	}

	/**
	 * Set config property
	 * @param string $prop property name
	 * @param mixed $value new property value
	 * @return boolean
	 */
	public function set($prop, $value)
	{
		return (new \CIBlockElement())->SetPropertyValueCode(
			$this->appConfig['ID'],
			$prop,
			$value
		);
	}
}
