<?php
/**
 * Determines what template should be loaded when a customize-able easy beer lister element is displayed.
 * This is also used to generate template content via the REST API. See /assets/js/ebl.js for more info
 * @author: Alex Standiford
 * @date  : 10/18/17
 */


namespace ebl\app;


use ebl\app\beerList\inSeasonList;
use ebl\app\beerList\outOfSeasonList;
use ebl\app\beerList\tapList;
use ebl\app\beerList\yearRoundList;
use ebl\core\ebl;

if(!defined('ABSPATH')) exit;

class templateLoader extends ebl{

  const SUPPORTED_LOCATIONS = ['shortcode','widget', 'single', 'wrapper', 'archive', 'component'];
  const SUPPORTED_TYPES = ['widget', 'random-beer', 'on-tap', 'heading', 'filter', 'header', 'beer', 'single', 'archive', 'beers', 'beer-info-basic', 'beer-glass', 'beer-pairings', 'beer-availability','beer-gallery', 'beer-video', 'beer-stats', 'related-beers'];
  const THEME_DIRECTORY = 'easy-beer-lister/';
  private $defaults = ['load_as_buffer' => false, 'post_id' => false];
  public $type;
  private $isPartial = false;
  public $parent = null;
  public $location;
  public $template;
  public $postID;
  public static $buffer;

  public function __construct($location = 'wrapper', $type = 'beers', $args = []){
    $args = wp_parse_args($args, $this->defaults);
    $this->location = (string)$location;
    $this->type = (string)$type;
    self::$buffer = $args['load_as_buffer'] ? self::$buffer = '' : self::$buffer = false;
    $this->postID = $args['post_id'];
    parent::__construct();
  }

  /**
   * Required on every object to check for errors
   * @return mixed
   */
  protected function checkForErrors(){
    if(!in_array($this->type, self::SUPPORTED_TYPES)){
      $supported_types = implode(',', self::SUPPORTED_TYPES);
      $this->throwError('templateLoader01', 'templateLoader error: The specified type "'.$this->type.'" is not supported. Supported types are the following: '.$supported_types);
    }
    if(!in_array($this->location, self::SUPPORTED_LOCATIONS)){
      $supported_locations = implode(',', self::SUPPORTED_LOCATIONS);
      $this->throwError('templateLoader02', 'templateLoader error: The specified location "'.$this->location.'" is not supported. Supported locations are the following: '.$supported_locations);
    }
    if(!ebl_get_beer_list() instanceof beerList && !$this->postID){
      $this->throwError('templateLoader03', 'There is no beer or beer list to display. Before running the template loader, be sure to create an instance of the Beer List using the beerList() query, or pass a post ID in the arguments array as ["post_id" => ID]');
    }

    return null;
  }

  /**
   * Finds the correct template to load for the current template
   * @return string
   */
  public function findTemplate(){
    if($this->hasErrors()) return false;
    $located = false;

    if(!$this->hasErrors()){
      $file = trailingslashit($this->location).$this->type.'.php';
      switch(true){
        //Check Stylesheet directory first (Child Theme)
        case file_exists(trailingslashit(get_stylesheet_directory()).self::THEME_DIRECTORY.$file):
          $located = trailingslashit(get_stylesheet_directory()).self::THEME_DIRECTORY.$file;
          break;
        //Check Template directory Second (Parent Theme)
        case file_exists(trailingslashit(get_template_directory()).self::THEME_DIRECTORY.$file):
          $located = trailingslashit(get_template_directory()).self::THEME_DIRECTORY.$file;
          break;
        //Check filtered custom template directory, if it's set.
        case (apply_filters('ebl_custom_template_directory_root', '', $this) !== '' && file_exists(trailingslashit(apply_filters('ebl_custom_template_directory_root', '', $this)).$file)):
          $located = trailingslashit(apply_filters('ebl_custom_template_directory_root', '', $this)).$file;
          break;
        //If nothing else exists, go ahead and get the default
        default:
          if($this->fileExists(EBL_TEMPLATE_DIRECTORY.$file)) $located = EBL_TEMPLATE_DIRECTORY.$file;
          break;
      }
    }

    return $located;
  }

  /**
   * Includes the specified template
   * @return bool Returns true if the file was successfully included
   */
  public function loadTemplate(){

    if($this->hasErrors()) return false;
    $template = $this->findTemplate();
    $this->updateBuffer();
    do_action(EBL_PREFIX.'_before_include_template', $this);
    include($template);
    do_action(EBL_PREFIX.'_after_include_template', $this);
    $this->updateBuffer();

    return self::$buffer === false ? $this->hasErrors() == false && $template !== false : $this->updateBuffer();
  }

  private function updateBuffer(){
    global $ebl_buffer;
    if(self::$buffer !== false){
      self::$buffer = ob_get_clean();
      $ebl_buffer .= self::$buffer;
      ob_start();
    }

    return $ebl_buffer;
  }

  /**
   * Gets a template partial from the specified template
   *
   * @param string $location
   * @param string $type
   *
   * @return bool
   */
  public function getPartial($location = 'single', $type = 'beer'){
    if($this->hasErrors()) return false;
    $this->updateBuffer();
    $partial = new self($location, $type, ['load_as_buffer' => self::$buffer]);
    $partial->isPartial = true;
    $partial->parent = ['location' => $this->location, 'type' => $this->type];
    do_action(EBL_PREFIX.'_before_partial_include', $this);
    $template_loaded = $partial->loadTemplate() === false ? false : true;
    do_action(EBL_PREFIX.'_after_partial_include', $this);
    $this->updateBuffer();

    return $template_loaded;
  }

  /**
   * Loads up the Wrapper Classes for the current template
   * @return array | bool
   */
  public function getArgs($args){
    if($this->hasErrors()) return false;
    $args = apply_filters(EBL_PREFIX.'_template_wrapper_args', $args, $args, $this);

    if(!is_array($args)){
      $this->throwError('templateLoader03', 'Filtered wrapper args returned a '.gettype($args).', expected array');

      return false;
    }

    return implode(' ', $args);
  }

  /**
   * Checks to see if the currently loaded template is a partial
   * @return bool
   */
  public function isPartial(){
    return $this->isPartial;
  }

  /**
   * Gets the classes for current wrapper item
   * @return bool|string
   */
  public function wrapperClasses($extra_classes = []){
    global $post;
    if($this->hasErrors()) return false;
    $default_classes = [
      EBL_PREFIX.'-'.$this->type,
      EBL_PREFIX.'-'.$this->location,
      EBL_PREFIX.'-'.$this->type.'-'.$this->location,
    ];
    $classes = wp_parse_args($default_classes, $extra_classes);
    $classes = $this->getArgs(apply_filters(EBL_PREFIX.'_template_wrapper_classes', $classes, $classes, $this));
    if(!$this->isPartial() || $this->location != 'wrapper'){
      $classes = 'class="'.$classes.'"';
    }
    else{
      $classes = post_class($classes, $post);
    }

    return $classes;
  }

  /**
   * Generates arguments for wrapper elements
   * @return bool|string
   */
  public function wrapperArgs(){
    if($this->hasErrors()) return false;
    $args = apply_filters(EBL_PREFIX.'_template_wrapper_args', [], $this);
    $result = '';
    foreach($args as $key => $value){
      $result .= sanitize_title($key).'="'.sanitize_title($value).'"';
    }

    return $result;
  }

  /**
   * Loads a template from the API.
   *
   * @param \WP_REST_Request $req
   *
   * @return array
   */
  public static function loadTemplateFromAPI(\WP_REST_Request $req){
    $location = $req->get_param('location') ? $req->get_param('location') : 'wrapper';
    $type = $req->get_param('type') ? $req->get_param('type') : 'beers';
    $query = $req->get_param('query') ? (array)$req->get_param('query') : [];
    $query['load_as_buffer'] = true;
    switch($query){
      case isset($query['post_id']):
        break;
      case $query['type'] == 'tapList':
        new tapList($query);
        break;
      case $query['type'] == 'outOfSeason':
        new outOfSeasonList($query);
        break;
      case $query['type'] == 'inSeason':
        new inSeasonList($query);
        break;
      case $query['type'] == 'yearRound':
        new yearRoundList($query);
        break;
      default:
        new beerList($query);
    }

    unset($query['type']);
    $template = new self($location, $type, $query);

    $template->isApi = true;

    $result['template'] = $template->loadTemplate();

    return $template->apiReturn($result);
  }
}