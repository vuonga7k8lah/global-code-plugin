<?php

namespace VDGlobalCode\Helpers;

/**
 * Class Filtered
 * @package HSBlogCore\Helpers
 */
class Filter {
	private static array $aFiltered = [];

	/**
	 * @param $name
	 * @param bool $value
	 *
	 * @return bool
	 */
	public static function setFiltered( $name, bool $value = true ): bool {
		if ( ! self::isFiltered( $name ) ) {
			self::$aFiltered[ $name ] = $value;
		}

		return true;
	}

	public static function removeFiltered( $name ): bool {
		unset( self::$aFiltered[ $name ] );

		return true;
	}


	/**
	 * @param $name
	 *
	 * @return bool
	 */
	public static function isFiltered( $name ): bool {
		return isset( self::$aFiltered[ $name ] );
	}
}
