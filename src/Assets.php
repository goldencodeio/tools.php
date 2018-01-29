<?php

namespace goldencode\Helpers;

class Assets
{
	private $instance = null;
	private $options = [];
	private $assets = [];

	/**
	 * AppConfig constructor.
	 * @param array $options
	 */
	public function __construct(array $options = []) {
		if ($this->instance) return $this->instance;

		$this->options = array_merge($this->options, [
			'dir' => null
		], $options);

		$this->instance = $this;
		return $this;
	}

	/**
	 * Add build version query param to asset path
	 * @param string $path
	 * @return string
	 */
	public function prependVersion($path) {
		return $path . ($_ENV['BUILD_HASH'] ? '?v=' . $_ENV['BUILD_HASH'] : '');
	}
}
