<?php
/*
Plugin Name: BrewBuddy
Description: Manage Your Beers, Beer Pairings, and What's on Tap Easily
Version:     1.0
Author:      Alex Standiford
Author URI:  http://www.fillyourtaproom.com
*/

/*--- REGISTERS POST TYPE ---*/
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
      'menu_position'      => 5,
      'supports'           => array( 'title','editor','excerpt', 'revisions', 'thumbnail' ),
      'menu_icon'          => plugin_dir_url( __FILE__ ).'/media/icon.png',
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
/*--- REGISTERS TAXONOMY ---*/
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

/*--- CUSTOM STYLES ---*/
function tasbb_beer_styles_init(){
  $styles = [
    'style/tasbb.css'
  ];
  $scripts = [
    'js/tasbb.js'
  ];

  $result .= '<!-- BEGIN TASBB STYLES -->';
  foreach($styles as $style){
    $result .= '<link rel="stylesheet" href="';
    $result .= plugin_dir_url(__FILE__).$style;
    $result .= '">';
  }
  $result .= '<!-- BEGIN TASBB SCRIPTS -->';
  foreach($scripts as $script){
    $result .= '<script src="';
    $result .= plugin_dir_url(__FILE__).$script;
    $result .= '"></script>';
  }
  echo $result;
}
add_action('wp_footer','tasbb_beer_styles_init');

include_once(dirname(__FILE__).'/fields.php');
include_once(dirname(__FILE__).'/functions.php');
?>