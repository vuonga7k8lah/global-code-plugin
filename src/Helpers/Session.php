<?php

namespace VDGlobalCode\Helpers;

class Session {
	protected static $expiration = 900;

	public static function generatePrefix( $name ) {
		return strpos( $name, 'wiloke-' ) !== 0 ? 'wiloke-' . trim( $name ) : $name;
	}

	public static function setCurrentDomainId( $shopId ) {
		self::setSession( 'currentShopID', $shopId );
	}

	public static function setCurrentShopName( $shopName ) {
		self::setSession( 'currentShopName', $shopName );
	}

	public static function getCurrentShopName(): string {
		return (string) self::getSession( 'currentShopName' );
	}

	public static function getCurrentDomainId(): int {
		return (int) self::getSession( 'currentShopID' );
	}

	public static function sessionStart( $sessionID = null ) {
		global $pagenow;
		if ( $pagenow == 'site-health.php' ||
		     ( is_admin() && isset( $_GET['page'] ) && $_GET['page'] == 'site-health' ) ) {
			session_id( $sessionID );
		}

		if ( ! headers_sent() && ( session_status() == PHP_SESSION_NONE || session_status() === 1 ) ) {
			session_start();
		}
	}

	public static function getErrors() {
		$aErrors = self::getSession( 'errors' );

		return empty( $aErrors ) ? [] : $aErrors;
	}

	public static function addError( $key, $msg ) {
		$aErrors = self::getErrors();

		if ( empty( $key ) ) {
			$aErrors[] = $msg;
		} else {
			$aErrors[ $key ] = $msg;
		}

		self::setSession( 'errors', $aErrors );
	}

	public static function removeError( $key ) {
		$aErrors = self::getErrors();
		unset( $aErrors[ $key ] );

		self::setSession( 'errors', $aErrors );
	}

	public static function getError( $key, $defaultMsg = '' ) {
		$aErrors = self::getErrors();

		return array_key_exists( $key, $aErrors ) ? $aErrors[ $key ] : $defaultMsg;
	}

	public static function getSessionID() {
		session_start();
		var_export( session_id() );
	}

	public static function setSession( $name, $value, $sessionID = null ) {
		if ( empty( session_id() ) ) {
			self::sessionStart( $sessionID );
		}
		$_SESSION[ self::generatePrefix( $name ) ] = maybe_serialize( $value );

		return $value;
	}

	public static function destroySession( $name = '' ) {
		$name = self::generatePrefix( $name );
		if ( empty( $name ) ) {
			session_destroy();
		}

		unset( $_SESSION[ $name ] );
	}

	public static function getSession( $name, $thenDestroy = false ) {
		$name = self::generatePrefix( $name );
		self::sessionStart( $name );
		$value = $_SESSION[ $name ] ?? '';

		if ( empty( $value ) ) {
			return false;
		}

		if ( $thenDestroy ) {
			self::destroySession( $name );
		}

		return maybe_unserialize( $value );
	}
}
