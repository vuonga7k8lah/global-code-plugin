<?php

namespace VDGlobalCode\GlobalCode\Models;

use VDGlobalCode\Helpers\TraitGlobalCodeScss;
use VDGlobalCode\Helpers\TraitGlobalCodeJs;
use VDGlobalCode\Illuminate\Message\MessageFactory;
use VDGlobalCode\Illuminate\Prefix\AutoPrefix;
use WP_Query;

class GlobalCodeModel
{
	use TraitGlobalCodeScss, TraitGlobalCodeJs;

	private array $aArgs;

	private function handleArgs(array $aArgs): array
	{
		$aArgs = wp_parse_args($aArgs, [
			'ids'     => 0,
			'id'      => 0,
			'limit'   => 20,
			'page'    => 1,
			'orderby' => 'name',
			'order'   => 'ASC',
			's'       => '',
			'status'  => 'any',
		]);
		if (isset($aArgs['status']) && !empty($aArgs['status'])) {
			if ($aArgs['status'] != 'any') {
				$this->aArgs['post_status'] = $aArgs['status'] == 'active' ? 'publish' : 'draft';
			} else {
				$this->aArgs['post_status'] = ['draft', 'publish'];
			}
			unset($this->aArgs['status']);
		} else {
			$this->aArgs['post_status'] = ['draft', 'publish'];
		}
		if (isset($aArgs['limit']) && $aArgs['limit'] <= 50) {
			$this->aArgs['posts_per_page'] = $aArgs['limit'];
		} else {
			$aArgs['posts_per_page'] = 50;
		}

		if (isset($aArgs['page']) && $aArgs['page']) {
			$this->aArgs['paged'] = $aArgs['page'];
		} else {
			$this->aArgs['paged'] = 1;
		}

		if (empty($aArgs['s'])) {
			unset($this->aArgs['s']);
		}

		if (isset($aArgs['nopaging']) && !empty($aArgs['nopaging'])) {
			$this->aArgs['nopaging'] = $aArgs['nopaging'];
		}

		if (isset($aArgs['hadUpdateFile']) && !empty($aArgs['hadUpdateFile'])) {
			$this->aArgs['hadUpdateFile'] = $aArgs['hadUpdateFile'];
		}

		if (isset($aArgs['postType']) && !empty($aArgs['postType'])) {
			$this->aArgs['post_type'] = $aArgs['postType'];
		} else {
			$this->aArgs['post_type'] = [
				AutoPrefix::namePrefix('plugins'),
				AutoPrefix::namePrefix('utils')
			];
		}
		return $this->aArgs;
	}

	public function getGlobalCodes(array $aArgs): array
	{

		$oQuery = new WP_Query($this->handleArgs($aArgs));
		$aResponse['maxPages'] = 0;
		$aResponse['items'] = [];

		if (!$oQuery->have_posts()) {
			wp_reset_postdata();
			return MessageFactory::factory()->success('We found no items', $aResponse);
		}

		$aItems = [];
		$aScss = [];
		$aJs = [];
		while ($oQuery->have_posts()) {
			$oQuery->the_post();
			$postID = $oQuery->post->ID;
			if (isset($aArgs['hadUpdateFile'])) {
				$aScss[] = $this->getDataScss($postID);
				$aJs[] = $this->getDataJs($postID);
			} else {
				$aItems[] = [
					'id'   => $postID,
					'name' => get_the_title($postID),
					'scss' => $this->getDataScss($postID),
					'js'   => $this->getDataJs($postID),
					'type' => AutoPrefix::removePrefix(get_post_type($postID))
				];
			}
		}
		wp_reset_postdata();
		$aResponse['maxPages'] = $oQuery->max_num_pages;
		$aResponse['items'] = isset($aArgs['hadUpdateFile']) ? [
			'js'   => $aJs,
			'scss' => $aScss
		] : $aItems;
		return MessageFactory::factory()->success(
			sprintf('We found %s items', count($aItems)),
			$aResponse
		);
	}
}