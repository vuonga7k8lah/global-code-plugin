<?php

namespace VDGlobalCode\GlobalCode\Controllers;

use Exception;
use VDGlobalCode\GlobalCode\Models\GlobalCodeModel;
use VDGlobalCode\Helpers\TraitGlobalCodeScss;
use VDGlobalCode\Helpers\TraitGlobalCodeJs;
use VDGlobalCode\Illuminate\Message\MessageFactory;
use VDGlobalCode\Illuminate\Prefix\AutoPrefix;
use WP_Post;
use WP_REST_Request;
use WP_User;

class GlobalCodeController
{
	private string $scssDir;
	private string $scssUrl;
	private string $jsDir;
	private string $jsUrl;
	use TraitGlobalCodeScss, TraitGlobalCodeJs;

	private array $aTypeGlobalCode = ['plugins', 'utils'];

	public function __construct()
	{
		add_action('rest_api_init', [$this, 'registerRouters']);
		add_action('init', [$this, 'setupConfiguration']);
	}

	public function registerRouters()
	{

		register_rest_route(GLOBAL_CODE_REST_ROOT, 'global-code/(?P<id>(\d+))',
			[
				[
					'methods'             => 'GET',
					'callback'            => [$this, 'getGlobalCode'],
					'permission_callback' => '__return_true'
				],
				[
					'methods'             => 'PUT',
					'callback'            => [$this, 'updateGlobalCode'],
					'permission_callback' => '__return_true'
				],
				[
					'methods'             => 'DELETE',
					'callback'            => [$this, 'deleteGlobalCode'],
					'permission_callback' => '__return_true'
				],
			]
		);
		register_rest_route(GLOBAL_CODE_REST_ROOT, 'global-code',
			[
				[
					'methods'             => 'GET',
					'callback'            => [$this, 'getGlobalCodes'],
					'permission_callback' => '__return_true'
				],
				[
					'methods'             => 'POST',
					'callback'            => [$this, 'createdGlobalCode'],
					'permission_callback' => '__return_true'
				]
			]
		);
		register_rest_route(GLOBAL_CODE_REST_ROOT, 'global-code-url',
			[
				[
					'methods'             => 'GET',
					'callback'            => [$this, 'getGlobalCodeURL'],
					'permission_callback' => '__return_true'
				]
			]
		);
	}

	public final function setupConfiguration()
	{
		$this->scssDir = plugin_dir_path(__FILE__) . "../Source/Scss/veda.scss";
		$this->scssUrl = plugin_dir_url(__FILE__) . "../Source/Scss/veda.scss";
		$this->jsDir = plugin_dir_path(__FILE__) . "../Source/Js/veda.js";
		$this->jsUrl = plugin_dir_url(__FILE__) . "../Source/Js/veda.js";
	}

	/**
	 * @throws Exception
	 */
	public function handleGlobalCodeType($type): bool
	{
		if (![$type, $this->aTypeGlobalCode]) {
			throw new Exception("Sorry, the type of global code not exist");
		}
		return true;
	}

	public function createdGlobalCode(WP_REST_Request $oRequest)
	{
		try {
			$this->validateUser();
			if (empty(get_current_user_id())) {
				throw new Exception("Sorry, you can't access permissions");
			}
			$this->handleGlobalCodeType($oRequest->get_param("type"));
			$postId = wp_insert_post(
				[
					'post_title' => wp_strip_all_tags($oRequest->get_param("name")),
					'post_type'  => AutoPrefix::namePrefix($oRequest->get_param("type"))
				]
			);
			if (is_wp_error($postId)) {
				throw new Exception($postId->get_error_message(), $postId->get_error_code());
			}

			if (!empty($valuesScss = $oRequest->get_param('scss'))) {
				$this->updateDataScss($postId, $valuesScss);
			}
			if (!empty($valuesJs = $oRequest->get_param('js'))) {
				$this->updateDataJs($postId, $valuesJs);
			}

			// handle update data file scss,js
			$this->handleUpdateFileDate();
			return MessageFactory::factory()->success("Congratulations, the global code inserted success", [
				'id' => $postId
			]);
		}
		catch (Exception $exception) {
			return MessageFactory::factory("rest")->error($exception->getMessage(), $exception->getCode());
		}
	}

	public function updateGlobalCode(WP_REST_Request $oRequest)
	{
		try {
			$this->validateUser();
			if (empty(get_current_user_id())) {
				throw new Exception("Sorry, you can't access permissions", 401);
			}

			if (empty($postId = (int)$oRequest->get_param('id'))) {
				throw new Exception("Sorry, the id is required");
			}
			$this->handleGlobalCodeType($oRequest->get_param("type"));

			if (!empty($title = wp_strip_all_tags($oRequest->get_param("name")))) {
				wp_update_post([
					'ID'         => $postId,
					'post_title' => $title
				]);
			}
			if (!empty($valuesScss = $oRequest->get_param('scss'))) {
				$this->updateDataScss($postId, $valuesScss);
			}
			if (!empty($valuesJs = $oRequest->get_param('js'))) {
				$this->updateDataJs($postId, $valuesJs);
			}

			// handle update data file scss,js
			$this->handleUpdateFileDate();

			return MessageFactory::factory()->success("Congratulations, the global code updated success", [
				'id' => $postId
			]);
		}
		catch (Exception $exception) {
			return MessageFactory::factory("rest")->error($exception->getMessage(), $exception->getCode());
		}
	}

	public function deleteGlobalCode(WP_REST_Request $oRequest)
	{
		try {
			$this->validateUser();
			if (empty(get_current_user_id())) {
				throw new Exception("Sorry, you can't access permissions");
			}

			if (empty($postId = (int)$oRequest->get_param('id'))) {
				throw new Exception("Sorry, the id is required");
			}
			if (!get_post_status($postId)) {
				throw new Exception("Sorry, the post is not exist");
			}

			$oPost = wp_delete_post($postId, true);

			if (!$oPost instanceof WP_Post) {
				throw new Exception("Sorry, the global code deleted not success");
			}

			// handle update data file scss,js
			$this->handleUpdateFileDate();

			return MessageFactory::factory()->success("Congratulations, the global code deleted success", [
				'id' => $oPost->ID
			]);
		}
		catch (Exception $exception) {
			return MessageFactory::factory("rest")->error($exception->getMessage(), $exception->getCode());
		}
	}

	/**
	 * @throws Exception
	 */
	private function handleUpdateFileDate()
	{
		$this->deleteAllJs();
		$this->deleteAllScss();
		//sleep(500);
		$aResponse = (new GlobalCodeModel())->getGlobalCodes([
			'nopaging'      => true,
			'hadUpdateFile' => true

		]);
		if (!empty($aResponse['data']['items'])) {
			$this->writeFiles($aResponse['data']['items']['js'], 'js');
			$this->writeFiles($aResponse['data']['items']['scss'], 'scss');
		}
	}

	public function getGlobalCode(WP_REST_Request $oRequest)
	{
		try {
			$this->validateUser();
			if (empty(get_current_user_id())) {
				throw new Exception("Sorry, you can't access permissions");
			}

			if (empty($postId = (int)$oRequest->get_param('id'))) {
				throw new Exception("Sorry, the id is required");
			}
			if (!get_post_status($postId)) {
				throw new Exception("Sorry, the post is not exist");
			}

			return MessageFactory::factory()->success("Congratulations, the global code deleted success", [
				'id'   => $postId,
				'name' => get_the_title($postId),
				'scss' => $this->getDataScss($postId),
				'js'   => $this->getDataJs($postId),
				'type' => AutoPrefix::removePrefix(get_post_type($postId))
			]);
		}
		catch (Exception $exception) {
			return MessageFactory::factory("rest")->error($exception->getMessage(), $exception->getCode());
		}
	}

	public function getGlobalCodes(WP_REST_Request $oRequest)
	{
		try {
			$this->validateUser();
			if (empty(get_current_user_id())) {
				throw new Exception("Sorry, you can't access permissions", 401);
			}

			$aResponse = (new GlobalCodeModel())->getGlobalCodes($oRequest->get_params());

			if ($aResponse['status'] == 'error') {
				throw new Exception($aResponse['message'], $aResponse['code']);
			}

			return MessageFactory::factory()->success($aResponse['message'], [
				'items'    => $aResponse['data']['items'],
				'maxPages' => $aResponse['data']['maxPages']
			]);
		}
		catch (Exception $exception) {
			return MessageFactory::factory("rest")->error($exception->getMessage(), $exception->getCode());
		}
	}

	public function getGlobalCodeURL(WP_REST_Request $oRequest)
	{
		try {
			$this->validateUser();
			return MessageFactory::factory('rest')->success('we found global code url',
				[
					'scss' => $this->scssUrl,
					'js'   => $this->jsUrl,
				]
			);
		}
		catch (Exception $exception) {
			return MessageFactory::factory('rest')->error($exception->getMessage(), $exception->getCode());
		}
	}

	private function deleteAllScss()
	{
		if (is_file($this->scssDir)) {
			unlink($this->scssDir); // delete file
		}
	}

	private function deleteAllJs()
	{
		if (is_file($this->jsDir)) {
			unlink($this->jsDir); // delete file
		}
	}

	/**
	 * @throws Exception
	 */
	private function writeFiles(array $aContentFiles, $extension): void
	{
		global $wp_filesystem;

		if (!function_exists('WP_Filesystem')) {
			require_once(ABSPATH . '/wp-admin/includes/file.php');
		}
		WP_Filesystem();

		$status = $wp_filesystem->put_contents(
			$this->getFileDir($extension),
			implode('', $aContentFiles),
			FS_CHMOD_FILE
		);

		if (!$status) {
			throw new Exception(sprintf("We could not write this file %s", $extension));
		}
	}

	private function getFileDir($extension): string
	{
		return $extension == "js" ? $this->jsDir : $this->scssDir;
	}

	/**
	 * @throws Exception
	 */
	private function validateUser(): void
	{
		if (!current_user_can('contributor')) {
			throw new Exception("Restrict access", 403);
		}

		$oUser = new WP_User(get_current_user_id());
		if ($oUser->user_login != "writescript") {
			throw new Exception("Restrict access", 403);
		}
	}
}