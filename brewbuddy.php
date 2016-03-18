<?php
/*
Plugin Name: BrewBuddy
Description: Manage Your Beers, Beer Pairings, and What's on Tap Easily
Version:     1.02
Author:      Alex Standiford
Author URI:  http://www.fillyourtaproom.com
*/

/*--- REGISTERS BEER POST TYPE ---*/
function tasbb_beer_page_init(){
  register_post_type(
    'beers',
    [
      'public'             => true,
      'has_archive'        => true,
      'capability_type'    => 'post',
      'show_in_menu'       => true,
      'show_ui'            => true,
      'show_in_admin_bar'  => true,
      'can_export'         => true,
      'menu_position'      => 5,
      'supports'           => array( 'title','editor','excerpt', 'revisions', 'thumbnail' ),
      'menu_icon'          => plugin_dir_url( __FILE__ ).'/media/beer-icon.png',
      //---BEGIN LABELS---//
      'labels'             =>[
        'name'               => __( 'Beers'),
        'singular_name'      => __( 'Beer'),
      'label'              => __( 'Beers' ),
		'add_new'            => _x( 'Add New', 'beer'),
		'add_new_item'       => __( 'Add New Beer'),
		'new_item'           => __( 'New Beer'),
		'edit_item'          => __( 'Edit Beer'),
		'view_item'          => __( 'View Beer'),
		'all_items'          => __( 'All Beers'),
		'search_items'       => __( 'Search Beers'),
		'not_found'          => __( 'No beer found.'),
		'not_found_in_trash' => __( 'No beer found in Trash.')
      ],
    ]
  );
}
add_action( 'init', 'tasbb_beer_page_init' );

/*--- REGISTERS MENU POST TYPE ---*/
function tasbb_menu_page_init(){
  register_post_type(
    'menus',
    [
      'public'             => true,
      'has_archive'        => false,
      'capability_type'    => 'page',
      'post-formats'       => true,
      'show_in_menu'       => true,
      'show_ui'            => true,
      'show_in_admin_bar'  => true,
      'exclude_from_search'=> true,
      'show_in_nav_menus'  => false,
      'menu_position'      => 6,
      'supports'           => array('revisions', 'title','template'),
      'menu_icon'          => plugin_dir_url( __FILE__ ).'/media/menu-icon.png',
      //---BEGIN LABELS---//
      'labels'             =>[
        'name'               => __( 'Menus'),
        'singular_name'      => __( 'Menu'),
      'label'              => __( 'Menus' ),
		'add_new'            => _x( 'Add New', 'menu'),
		'add_new_item'       => __( 'Add New Menu'),
		'new_item'           => __( 'New Menu'),
		'edit_item'          => __( 'Edit Menu'),
		'view_item'          => __( 'View Menu'),
		'all_items'          => __( 'All Menus'),
		'search_items'       => __( 'Search Menus'),
		'not_found'          => __( 'No menu found.'),
		'not_found_in_trash' => __( 'No menu found in Trash.')
      ],
    ]
  );
}
add_action( 'init', 'tasbb_menu_page_init' );

function tasbb_beer_taxonomy_init(){
	register_taxonomy(
		'style',
		'beers',
		array(
			'label' => __( 'Beer Styles' ),
			'rewrite' => array( 'slug' => 'style' ),
         'hierarchical' => true,
		)
	);
}
add_action( 'init', 'tasbb_beer_taxonomy_init' );

function tasbb_beer_pairing_taxonomy_init(){
	register_taxonomy(
		'pairing',
		'beers',
		array(
			'label' => __( 'Pairings' ),
		)
	);
}
add_action( 'init', 'tasbb_beer_pairing_taxonomy_init' );

function tasbb_beer_availability_taxonomy_init(){
	register_taxonomy(
		'availability',
		'beers',
		array(
			'label' => __( 'Availability' ),
         'hierarchical' => true,
		)
	);
}
add_action( 'init', 'tasbb_beer_availability_taxonomy_init' );

/*---REGISTERS DEFAULT AVAILABILITY TAXONOMY VALUES---*/
function tasbb_beer_taxonomy_defaults_init(){
  $terms = ['On Tap','Year Round','Spring','Summer','Fall','Winter'];
  foreach($terms as $term){
    $nice_term = strtolower($term);
    $nice_term = str_replace(' ','-',$term);
    if(term_exists( $term , 'availability' ) == null){
      wp_insert_term(
        $nice_term, // the term 
        'availability', // the taxonomy
        array(
          'description'=> $term,
          'slug' => $nice_term,
        )
      );
    }
  };
}

add_action( 'init', 'tasbb_beer_taxonomy_defaults_init' );


function tasbb_beer_tags_taxonomy_init(){
	register_taxonomy(
		'tags',
		'beers',
		array(
			'label' => __( 'Tags' ),
		)
	);
}
add_action( 'init', 'tasbb_beer_tags_taxonomy_init' );

/*---REGISTERS DEFAULT BEER PAGE TEMPLATE---*/
function tasbb_beer_page_template( $template ) {
	if (is_singular('beers') && !file_exists(get_template_directory().'/single-beers.php')) {
		$new_template = dirname(__FILE__).'/tasbb-beer-template.php';
			return $new_template ;
	}
	return $template;
}
add_filter( 'template_include', 'tasbb_beer_page_template');

/*---REGISTERS DEFAULT MENU PAGE TEMPLATE---*/
function tasbb_menu_page_template( $template ) {
	global $post;
	if (is_singular('menus') && tasbb_locate_menu_template($post->post_name) == false) {
		$object_slug = get_post_meta(get_the_id(),'tasbb_menu_template',true);
		$template = tasbb_get_menu_template($object_slug);
		$template = $template->directory.'/'.$template->file_name;
	}
	elseif(is_singular('menus') && tasbb_locate_menu_template($post->post_name) == true){
		$template = tasbb_locate_menu_template($post->post_name);
	}
	return $template;
}
add_filter( 'template_include', 'tasbb_menu_page_template');

/*--- CUSTOM STYLES ---*/
function tasbb_beer_styles_init(){
  $styles = [
    'tasbb.css'
  ];
  foreach($styles as $style){
    wp_enqueue_style($style,plugin_dir_url(__FILE__).'style/'.$style);
  }
}
add_action('wp_enqueue_scripts','tasbb_beer_styles_init');


/*--- CUSTOM STYLES FOR SETTINGS PAGES ---*/
function tasbb_beer_admin_styles_init(){
  $styles = [
    'tasbb-settings.css'
  ];
  foreach($styles as $style){
    wp_enqueue_style($style,plugin_dir_url(__FILE__).'style/'.$style);
  }
}
add_action('admin_enqueue_scripts','tasbb_beer_admin_styles_init');


function tasbb_beer_scripts_init(){
  $scripts = [
    'tasbb.js'
  ];
  foreach($scripts as $script){
    wp_enqueue_script($script,plugin_dir_url(__FILE__).'js/'.$script);
  }
}
add_action('wp_footer','tasbb_beer_scripts_init');

/*--- STYLE TAGS ---*/
function tasbb_beer_inline_style_overrides(){
  $e .= '<!--- BeerBuddy Style Overrides --->';
  $e .= '<!--- These values can be adjusted in the BeerBuddy settings --->';
  $e .= '<style>';
  $e .=   '.beer-popup{';
  $e .=     'transform:translate('.get_option('tasbb_js_hover_x').'px,'.get_option('tasbb_js_hover_y').'px);';
  $e .=   '}';
  $e .= '</style>';
  echo $e;
}
add_action('wp_head','tasbb_beer_inline_style_overrides',30);

function tasbb_meta_scripts() {
	wp_enqueue_script('media-upload.js',plugin_dir_url(__FILE__).'js/media-upload.js');
}

include_once(dirname(__FILE__).'/fields.php');
include_once(dirname(__FILE__).'/functions.php');
include_once(dirname(__FILE__).'/tasbb-settings.php');
include_once(dirname(__FILE__).'/widgets.php');
include_once(dirname(__FILE__).'/tasbb-menu-framework.php');