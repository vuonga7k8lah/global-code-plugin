<?php

namespace VDGlobalCode\GlobalCode\Controllers;

class GlobalCodeRegistration
{
	public function __construct()
	{
		add_action('init', [$this, 'registerManual']);
	}

	public function registerManual()
	{
		$aConfig = include plugin_dir_path(__FILE__) . '../Configs/PostType.php';
		foreach ($aConfig as $aPostType) {
			register_post_type(
				$aPostType['postType'],
				$aPostType
			);
		}
	}
}