<?php

namespace VDGlobalCode\Helpers;

use VDGlobalCode\Illuminate\Prefix\AutoPrefix;

trait TraitGlobalCodeScss
{
	private string $keyGlobalCodeScss = "scss";

	public function updateDataScss(int $postId, string $data): bool|int
	{

		return update_post_meta($postId, AutoPrefix::namePrefix($this->keyGlobalCodeScss), $data);
	}

	public function getDataScss(int $postId): string
	{
		return get_post_meta($postId, AutoPrefix::namePrefix($this->keyGlobalCodeScss), true);
	}
}