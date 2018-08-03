<?php

namespace goldencode\Helpers\Bitrix;

class Tools
{
	/**
	 * Normalize Bitrix menu nav to multidimensional array
	 * @param array $nav Bitrix menu nav array
	 * @return array normalized menu
	 */
	public static function normalizeMenuNav(array $nav) {
		foreach ($nav as $key => $arItem) {
			if ($arItem['DEPTH_LEVEL'] > 1) {
				for ($i = $key - 1; $i >= 0; $i--) {
					if ($nav[$i]['DEPTH_LEVEL'] < $arItem['DEPTH_LEVEL']) {
						$nav[$i]['CHILDREN'][] = $key;
						break;
					}
				}
			}
		}

		$children = function(&$item, &$list) use (&$children) {
			if (!empty($item['CHILDREN'])) {
				foreach ($item['CHILDREN'] as $key => $id) {
					$childItem = $list[$id];
					if (!empty($childItem['CHILDREN'])) $children($childItem, $list);
					$item['CHILDREN'][$key] = $childItem;
					unset($list[$id]);
				}
			}
		};

		foreach ($nav as $i => $arItem) $children($nav[$i], $nav);
		$nav = array_filter($nav);
		return array_values($nav);
	}

	/**
	 * Remove keys started with tilda (~)
	 * @param array $data
	 * @return array
	 */
	public static function removeTildaKeys(array $data) {
		$deleteKeys = array_filter(array_keys($data), function($key) {
			return strpos($key, '~') === 0;
		});
		foreach ($deleteKeys as $key) unset($data[$key]);
		return $data;
	}

	/**
	 * Cache $callback results using bitrix CPHPCache
	 * @param string $cacheId cache id
	 * @param callable $callback function to cache results
	 * @param any $args $callback function argument
	 * @param number $timeSeconds cache TTL
	 */
	public static function cacheResult($cacheId, callable $callback, $args = null, $timeSeconds = 36000000) {
		$obCache = new \CPHPCache();
		$cachePath = '/'.SITE_ID.'/'.$cacheId;
		if ($obCache->InitCache($timeSeconds, $cacheId, $cachePath)) {
			$vars = $obCache->GetVars();
			$result = $vars['result'];
		} elseif($obCache->StartDataCache()) {
			$result = $callback($args);
			$obCache->EndDataCache(['result' => $result]);
		}
		return $result;
	}

	/**
	 * Prevent clearing of Iblock cached data
	 * @return bool
	 */
	public static function disableIblockCacheClear() {
		while (\CIblock::isEnabledClearTagCache()) {
			\CIblock::disableClearTagCache();
		}
		return true;
	}
}
