<?php


namespace VDGlobalCode\Helpers;


class GlobalStore {
	public static array $aRestRequestInfo = [];

	public static function getRestRequestInfo( $field ) {
		return self::$aRestRequestInfo[ $field ] ?? '';
	}
}
