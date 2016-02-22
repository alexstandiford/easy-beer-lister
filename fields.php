<?php

// 1. customize ACF path
add_filter('acf/settings/path', 'my_acf_settings_path');
 
function my_acf_settings_path($path) {
    $path = dirname(__FILE__ ).'/acf/';
    return $path;
}
// 2. customize ACF dir
add_filter('acf/settings/dir', 'my_acf_settings_dir');
function my_acf_settings_dir( $dir ) {
    $dir = dirname(__FILE__ ).'/acf/';
    return $dir;
}

// 3. Hide ACF field group menu item
function remove_acf_menu() {
		remove_menu_page('edit.php?post_type=acf');
	}
	add_action( 'admin_menu', 'remove_acf_menu', 999);

// 4. Include ACF
include_once( dirname(__FILE__ ).'/acf/acf.php' );
include_once( dirname(__FILE__ ).'/acf-gallery/acf-gallery.php' );

// 5. Register fields

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_beer',
		'title' => 'Other Beer Information',
		'fields' => array (
			array (
				'key' => 'field_56c86b3d1ec5c',
				'label' => 'ABV',
				'name' => 'abv',
				'type' => 'number',
				'instructions' => 'Enter the ABV of the beer here (do not include the % sign)',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => 0,
				'max' => 100,
				'step' => '',
			),
			array (
				'key' => 'field_56c86a359ad75',
				'label' => 'IBU',
				'name' => 'ibu',
				'type' => 'number',
				'instructions' => 'Enter the IBU value of the beer here',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => '',
				'max' => '',
				'step' => '',
			),
			array (
				'key' => 'field_56c87f5d60a32',
				'label' => 'O.G.',
				'name' => 'og',
				'type' => 'number',
				'instructions' => 'Enter the original gravity of the beer',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => '',
				'max' => '',
				'step' => '',
			),
			array (
				'key' => 'field_56c86b811ec5d',
				'label' => 'Beer Video',
				'name' => 'video',
				'type' => 'text',
				'instructions' => 'Enter the URL of the related beer video.',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
			),
			array (
				'key' => 'field_56c87cccd20e5',
				'label' => 'Additional Photos',
				'name' => 'additional_photos',
				'type' => 'gallery',
				'instructions' => 'Add extra photos of the beer in-addition to the featured image',
				'preview_size' => 'medium',
				'library' => 'all',
			),
			array (
				'key' => 'field_56c87f9360a33',
				'label' => 'Untappd URL',
				'name' => 'untappd_url',
				'type' => 'text',
				'instructions' => 'Enter the Untappd URL for this beer',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'beers',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'default',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}

?>