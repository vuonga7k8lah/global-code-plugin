<?php


namespace VDGlobalCode\Helpers;


class ThemeOptions {
	public static array $aOptions = [];

	public static function getOptions() {
		if ( ! self::$aOptions ) {
			$aOptions       = get_option( 'wiloke_options' );
			self::$aOptions = empty( $aOptions ) ? [] : $aOptions;
		}

		return self::$aOptions;
	}

	public static function getField( $field, $default = '' ) {
		self::getOptions();

		return self::$aOptions[ $field ] ?? $default;
	}

	public static function getImgField( $field, $default = '' ) {
		$aImg = self::getField( $field );

		return ! empty( $aImg ) ? $aImg['url'] : $default;
	}
}
