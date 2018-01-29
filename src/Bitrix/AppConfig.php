<?php

namespace goldencode\Helpers\Bitrix;

use Bitrix\Main\Loader;

class AppConfig {
	private $instance = null;
	private $options = [];
	private $values = [];

	/**
	 * AppConfig constructor.
	 * @param array $options
	 * @throws \Bitrix\Main\LoaderException
	 */
	public function __construct(array $options) {
		if ($this->instance) return $this->instance;

		$this->options = array_merge($this->options, [
			'iblockCode' => 'AppConfig',
			'configProps' => []
		], $options);

		$this->values = $this->init();

		$this->instance = $this;
		return $this;
	}

	/**
	 * Get app config object-like array
	 * @return array
	 * @throws \Bitrix\Main\LoaderException
	 */
	public function init() {
		Loader::includeModule('iblock');
		$res = [];

		$appConfig = \CIBlockElement::GetList(
			['ACTIVE' => 'Y'],
			['IBLOCK_CODE' => $this->options['iblockCode']],
			false,
			false,
			array_map(function($key) {
				return 'PROPERTY_' . $key;
			}, $this->options['configProps'])
		)->Fetch();

		foreach ($this->options['configProps'] as $key) {
			$res[$key] = $appConfig['PROPERTY_' . $key . '_VALUE'];
		}

		return $res;
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
		$val = $this->values[$prop];
		return call_user_func($transform, $val ? $val : $default);
	}

	/**
	 * Return all values
	 * @return array
	 */
	public function getValues()
	{
		return $this->values;
	}
}
