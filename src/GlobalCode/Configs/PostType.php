<?php
namespace VDGlobalCode\Illuminate\Prefix;
return [
	[
		'labels'             => [
			'name'           => esc_html__('Global Code Type Plugins', GLOBAL_CODE_REST_NAMESPACE),
			'singular_name'  => esc_html__('Global Code Type Plugin', GLOBAL_CODE_REST_NAMESPACE),
			'menu_name'      => esc_html__('Global Code Type Plugins', GLOBAL_CODE_REST_NAMESPACE),
			'name_admin_bar' => esc_html__('Global Code Type Plugins', GLOBAL_CODE_REST_NAMESPACE),
		],
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => ['slug' => AutoPrefix::namePrefix('plugins')],
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => true,
		'menu_position'      => null,
		'supports'           => ['title', 'editor', 'thumbnail', 'author'],
		'postType'           => AutoPrefix::namePrefix('plugins')
	],
	[
		'labels'             => [
			'name'           => esc_html__('Global Code Type Utils', GLOBAL_CODE_REST_NAMESPACE),
			'singular_name'  => esc_html__('Global Code Type Util', GLOBAL_CODE_REST_NAMESPACE),
			'menu_name'      => esc_html__('Global Code Type Utils', GLOBAL_CODE_REST_NAMESPACE),
			'name_admin_bar' => esc_html__('Global Code Type Utils', GLOBAL_CODE_REST_NAMESPACE),
		],
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => ['slug' => AutoPrefix::namePrefix('utils')],
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => true,
		'menu_position'      => null,
		'supports'           => ['title', 'editor', 'thumbnail', 'author'],
		'postType'           => AutoPrefix::namePrefix('utils')
	]
];