<?php
/*
Plugin Name: Easy Beer Lister
Description: Easily Manage and Display What Beers are on-tap
Version: 2.0
Author: Fill Your Taproom, LLC
Author URI: http://www.fillyourtaproom.com
License: GPL2
*/

namespace ebl;

use ebl\app\templateLoader;
use ebl\core\cpt;
use ebl\admin\metaBox;
use ebl\core\integrateAssets;

if(!defined('ABSPATH')) exit;

class eblInit{
  private static $instance = null;
  /**
   * Array of files to include when the plugin is fired up. Specify directory relative to plugin root
   * @var array
   */
  private $core_includes = [
    'ebl.php',
    'cpt.php',
  ];

  private $app_includes = [
    'templateLoader.php',
    'beer.php',
    'beerList.php',
    'beerList/tapList.php',
    'beerList/inSeasonList.php',
    'beerList/outOfSeasonList.php',
    'beerList/yearRoundList.php',
    'glass.php',
    'functions.php',
    'widgets/randomBeer.php',
    'widgets/onTapWidget.php',
    'shortcode/beerShortcode.php',
    'shortcode/beerListShortcode.php',
  ];

  /**
   * These includes only fire up on the admin page
   * @var array
   */
  private $admin_includes = [
    'metaBox.php',
    'metaBoxField.php',
  ];

  /**
   * Array of widgets to register
   * @var array: Class name or function to register widget from
   */
  private $endpoints = [
    'template/get'            => [
      'route_nicename' => 'getTemplate',
      'methods'        => ['GET', 'POST'],
      'callback'       => 'ebl\app\templateLoader::loadTemplateFromAPI',
    ],

    //Beer List Endpoints
    'beer-list'               => [
      'route_nicename' => 'beerList',
      'methods'        => ['GET', 'POST'],
      'callback'       => 'ebl\app\beerList::getDataFromAPI',
    ],
    'beer-list/on-tap'        => [
      'route_nicename' => 'tapList',
      'methods'        => ['GET', 'POST'],
      'callback'       => 'ebl\app\beerList\tapList::getDataFromAPI',
    ],
    'beer-list/in-season'     => [
      'route_nicename' => 'inSeason',
      'methods'        => ['GET', 'POST'],
      'callback'       => 'ebl\app\beerList\inSeasonList::getDataFromAPI',
    ],
    'beer-list/out-of-season' => [
      'route_nicename' => 'outOfSeason',
      'methods'        => ['GET', 'POST'],
      'callback'       => 'ebl\app\beerList\outOfSeasonList::getDataFromAPI',
    ],
    'beer-list/year-round'    => [
      'route_nicename' => 'yearRound',
      'methods'        => ['GET', 'POST'],
      'callback'       => 'ebl\app\beerList\yearRoundList::getDataFromAPI',
    ],

    //Single Beer Endpoints
    'beer'                    => [
      'route_nicename' => 'beer',
      'methods'        => ['GET', 'POST'],
      'callback'       => 'ebl\app\beer::getDataFromAPI',
    ],
  ];

  private static $eblCoreData = [];


  private function __construct(){
  }

  /**
   * Fires up the plugin.
   * @return self
   */
  public static function getInstance(){
    if(!isset(self::$instance)){
      self::$instance = new self;
      self::$instance->_defineConstants();
      self::$instance->_includeCoreFiles();
      self::$instance->_includeAppFiles();
      if(is_admin()) self::$instance->_includeAdminFiles();
      add_action('wp_enqueue_scripts', [self::$instance, '_includeCoreScripts']);
      add_action('rest_api_init', [self::$instance, '_registerRestEndpoints']);
    }

    return self::$instance;
  }

  /**
   * Defines constants
   * @return void
   */
  private function _defineConstants(){
    define('EBL_URL', plugin_dir_url(__FILE__));
    define('EBL_PATH', plugin_dir_path(__FILE__));
    define('EBL_ASSETS_URL', EBL_URL.'assets/');
    define('EBL_ASSETS_PATH', EBL_PATH.'assets/');
    define('EBL_TEMPLATE_DIRECTORY', EBL_PATH.'templates/');
    define('EBL_TEXT_DOMAIN', 'ebl');
    define('EBL_PREFIX', 'ebl');
    define('EBL_REST_NAMESPACE', 'ebl/v2');
    define('EBL_VERSION', '2.0');
    define('EBL_DB_VERSION', '2.0');
  }

  /**
   * Loads in the core scripts and styles
   */
  public function _includeCoreScripts(){
    foreach($this->endpoints as $route_name => $route_args){
      self::$eblCoreData[$route_args['route_nicename']] = trailingslashit(get_rest_url()).trailingslashit(EBL_REST_NAMESPACE).$route_name;
    }
    wp_register_script('ebl', EBL_ASSETS_URL.'js/ebl.js', ['jquery']);
    wp_localize_script('ebl', 'eblArgs', self::$eblCoreData);
    wp_enqueue_script('ebl');
  }

  /**
   * Registers Rest API Endpoints
   */
  public function _registerRestEndpoints(){
    foreach($this->endpoints as $route_name => $route_args){
      unset($route_args['route_nicename']);
      register_rest_route(EBL_REST_NAMESPACE, '/'.$route_name, $route_args);
    }
  }

  /**
   * Grabs the files to include, and requires them
   * @return void
   */
  private function _includeCoreFiles(){
    foreach($this->core_includes as $include){
      require_once(EBL_PATH.'lib/core/'.$include);
    }
  }

  /**
   * Grabs the files to include, and requires them
   * @return void
   */
  private function _includeAppFiles(){
    foreach($this->app_includes as $include){
      require_once(EBL_PATH.'lib/app/'.$include);
    }
  }

  /**
   * Grabs the files to include, and requires them
   * @return void
   */
  private function _includeAdminFiles(){
    foreach($this->admin_includes as $include){
      require_once(EBL_PATH.'lib/admin/'.$include);
    }
  }
}


/**
 * Fires up the plugin, and wraps the startup with a few actions.
 * This allows addons to fire up just before, or just after Easy Beer Lister
 */
function rock_and_roll(){

  do_action('ebl_before_init');
  eblInit::getInstance();
  do_action('ebl_after_init');
  cpt::register();
  do_action('ebl_after_cpt_registration');
  wp_enqueue_script('ebl', EBL_ASSETS_URL.'js/ebl.js', ['jquery'], EBL_VERSION);
  do_action('ebl_after_enqueue_scripts');
  register_widget('\ebl\app\widget\randomBeer');
  register_widget('\ebl\app\widget\onTapWidget');
  do_action('ebl_after_register_widgets');
  add_shortcode('beer','\ebl\app\shortcode\beerShortcode::get');
  add_shortcode('beer_list','\ebl\app\shortcode\beerListShortcode::get');
  do_action('ebl_after_register_shortcodes');


  //Image Sizes
  add_image_size(EBL_PREFIX.'_bottom_label', 324, 550, true);
  add_image_size(EBL_PREFIX.'_top_label', 132, 88, true);
}

add_action('widgets_init', __NAMESPACE__.'\\rock_and_roll');

/**
 * Flushes permalinks on plugin activation
 */
function permalink_flush(){
  flush_rewrite_rules();
}

/**
 * Flushes permalinks on plugin activation
 */
register_activation_hook(__FILE__, 'flush_rewrite_rules');

/**
 * Set Up our Admin Meta Boxes
 */
function setup_meta_boxes(){
  $beer_meta = new metaBox('Beer Info', 'beers');
  add_action('add_meta_boxes', [$beer_meta, 'addMetaBox']);
  add_action('save_post', [$beer_meta, 'saveMetaData'], 10, 2);
}

add_action('load-post.php', __NAMESPACE__.'\\setup_meta_boxes');
add_action('load-post-new.php', __NAMESPACE__.'\\setup_meta_boxes');

/**
 * Overrides the messaging that shows up when a beer is updated/saved
 *
 * @param $msg
 *
 * @return mixed
 */
function override_default_beer_messages_in_editor_on_save($msg){
  global $post;
  $link = " <a href='".get_permalink($post->ID)."'>View Beer</a>";
  $msg['beers'] = array(
    0 => '',
    1 => "Beer updated.".$link,
    2 => 'Custom field updated.',
    3 => 'Custom field deleted.',

    4  => "Beer updated.".$link,
    5  => "Beer restored to revision".$link,
    6  => "All right! Your beer has been published. Cheers!".$link,
    7  => "Beer saved.".$link,
    8  => "Beer submitted.".$link,
    9  => "Beer scheduled.".$link,
    10 => "Beer draft updated.".$link,
  );

  return $msg;
}

add_filter('post_updated_messages', __NAMESPACE__.'\\override_default_beer_messages_in_editor_on_save', 10, 1);


/**
 * Adds extra image sizes to upload editor. This is useful for the beers edit page.
 *
 * @param $sizes
 *
 * @return array
 */
function add_image_sizes_to_upload_editor($sizes){
  $sizes = array_merge($sizes, array(
    EBL_PREFIX.'_bottom_label' => __('Bottom Beer Label'),
    EBL_PREFIX.'_top_label'    => __('Top Beer Label'),
  ));

  return $sizes;
}

add_filter('image_size_names_choose', __NAMESPACE__.'\\add_image_sizes_to_upload_editor');


function load_custom_single_beer_page_template($template){
  global $wp_query;
  if(is_singular('beers')){
    new \ebl\app\beerList($wp_query);
    $template = new templateLoader('wrapper', 'single');

    $template->loadTemplate();

    return false;
  }
  elseif(is_post_type_archive('beers') || is_tax(['style', 'pairing', 'tags'])){
    new \ebl\app\beerList($wp_query);
    $template = new templateLoader('wrapper', 'archive');

    $template->loadTemplate();

    return false;
  }

  return $template;
}

add_filter('template_include', __NAMESPACE__.'\\load_custom_single_beer_page_template');