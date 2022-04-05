<?php


namespace VDGlobalCode\Helpers;


class Redirection {
	public static function to($to = null, $aQuery = [])
	{
		$to = empty($to) ? home_url('/') : $to;

		wp_redirect(add_query_arg($aQuery, $to));
		exit();
	}
}
