<?php

namespace goldencode\Helpers\Bitrix;

use Bitrix\Main\Loader;

class IblockUtility {
	private static $iblocks = [];

	/**
	 * Find iblock id by its code.
	 * @param string $code
	 * @return bool|mixed
	 * @throws \Bitrix\Main\LoaderException
	 */
	public static function getIblockIdByCode($code) {
		if (self::$iblocks[$code]) return self::$iblocks[$code];
		Loader::includeModule('iblock');
		$iblock = \CIBlock::GetList([], ['ACTIVE' => 'Y', 'CODE' => $code])->Fetch();
		if (!$iblock) return false;
		self::$iblocks[$code] = $iblock['ID'];
		return self::$iblocks[$code];
	}
}
