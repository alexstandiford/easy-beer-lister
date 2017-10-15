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

use ebl\core\cpt;

if(!defined('ABSPATH')) exit;

class eblInit{
  private static $instance = null;
  /**
   * Array of files to include when the plugin is fired up. Specify directory relative to plugin root
   * @var array
   */
  private $core_includes = [
    'cpt.php',
    'ebl.php',
  ];

  private $app_includes = [
    'beer.php',
  ];

  /**
   * Array of widgets to register
   * @var array: Class name or function to register widget from
   */
  private $endpoints = [
    /*    'favorites/update'      => [
          'methods'  => 'POST',
          'callback' => 'ochc\carousel\video\favorites\favorite::apiUpdate',
        ],*/
  ];


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
      add_action('rest_api_init', array(self::$instance, '_registerRestEndpoints'));
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
    define('EBL_TEXT_DOMAIN', 'eblInit');
    define('EBL_PREFIX', 'eblInit');
    define('EBL_REST_NAMESPACE', 'eblInit/v2');
  }

  /**
   * Registers Rest API Endpoints
   */
  public function _registerRestEndpoints(){
    foreach($this->endpoints as $route_name => $route_args){
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
}

add_action('init', __NAMESPACE__.'\\rock_and_roll');

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