<?php

namespace VDGlobalCode\Helpers;

use VDGlobalCode\Illuminate\Prefix\AutoPrefix;

trait TraitGlobalCodeJs
{
	private string $keyGlobalCodeJs = "js";

	public function updateDataJs(int $postId, string $data): bool|int
	{
		return update_post_meta($postId, AutoPrefix::namePrefix($this->keyGlobalCodeJs), $data);
	}

	public function getDataJs(int $postId)
	{
		return get_post_meta($postId, AutoPrefix::namePrefix($this->keyGlobalCodeJs), true);
	}
}