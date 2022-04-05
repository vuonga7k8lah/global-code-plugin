<?php

namespace VDGlobalCode\Illuminate\Prefix;

class AutoPrefix {
	public static function namePrefix( $name ) {
		return strpos( $name, GLOBAL_CODE_PREFIX ) === 0 ? $name : GLOBAL_CODE_PREFIX . $name;
	}

	public static function removePrefix( string $name ): string {
		if ( strpos( $name, GLOBAL_CODE_PREFIX ) === 0 ) {
			$name = str_replace( GLOBAL_CODE_PREFIX, '', $name );
		}

		return $name;
	}
}
