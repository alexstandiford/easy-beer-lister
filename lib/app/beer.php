<?php
/**
 * Constructor for a single beer
 * @author: Alex Standiford
 * @date  : 10/14/17
 */


namespace ebl\app;


use DateTime;
use ebl\core\ebl;

if(!defined('ABSPATH')) exit;

class beer extends ebl{

  public $post;
  public $glass;


  public function __construct($post = null){
    $this->post = get_post($post);
    parent::__construct();
    if(!$this->hasErrors()){
      $GLOBALS[EBL_PREFIX.'_beer'] = $this;
    }
  }

  /**
   * Checks the post for any errors
   */
  function checkForErrors(){
    //If the post returned an error, capture that
    if(is_wp_error($this->post)){
      return $this->throwError($this->post);
    }
    //Confirms the post is a WP_Post object
    if(!$this->post instanceof \WP_Post){
      return $this->throwError('beer01', 'The input post specified is not a post. Are you sure you entered the correct ID, or post object?');
    }
    //Confirms the post is a Beers CPT
    if(get_post_type($this->post) !== 'beers'){
      return $this->throwError('beer02', 'The input post specified is not a beer, it is a '.get_post_type($this->post).'. The Beer object is designed to only work with the Beers Custom Post Type.');
    }

    return null;
  }

  /**
   * Gets the specified post meta value, and loads the meta values into the object if they're not yet loaded in
   *
   * @param $value
   *
   * @return mixed
   */
  public function getMetaValue($value){
    if($this->hasErrors()) return false; //Bail early if the object has any errors

    if(isset($this->$value)) return $this->$value; //Bail early if we've already loaded the values in
    $this->$value = get_post_meta($this->post->ID, EBL_PREFIX.'_'.$value, true);

    do_action(EBL_PREFIX.'_before_get_meta_value', $this, $value);
    if(!isset($this->$value)) $this->$value = false;

    return apply_filters(EBL_PREFIX.'_get_meta_value', $this->$value, $this, $value);
  }

  /**
   * Loads all of the meta values into the beer object.
   * This is designed to run in cases where you want to pass a complete beer object instead of dynamically loading
   * the content on-request. (such as an API call)
   */
  public function getMetaValues(){
    $this->getABV();
    $this->getIBU();
    $this->getOG();
    $this->getPrice();
    $this->getVideoUrl();
    $this->getGalleryItems();
    $this->getUntappdURL();
    $this->getBottomLabel();
    $this->getTopLabel();
    $this->getAvailabilityEndDate();
    $this->getAvailabilityStartDate();
    $this->isOnTap();
    $this->getSRM();
    $this->getGlassShape();
    $this->getStyle();
    $this->getPairings();
    $this->getTags();
  }

  /**
   * Get the terms from the specified taxonomy
   *
   * @param      $taxonomy
   * @param bool $single
   *
   * @return array|bool|\WP_Error
   */
  public function getTerms($taxonomy, $single = false, $args = []){
    if($this->hasErrors()) return false; //Bail early if the object has errors
    if(isset($this->$taxonomy)){
      $terms = $this->$taxonomy;
    }
    else{
      //Check to see if the taxonomy exists. if it does, get it. Otherwise, throw an error and bail.
      if(taxonomy_exists($taxonomy)){
        $terms = wp_get_post_terms($this->post->ID, $taxonomy, $args);
      }
      else{
        $this->throwError('beer03', "The taxonomy {$taxonomy} does not exist");

        return false;
      }
    }

    $terms = apply_filters(EBL_PREFIX.'_get_terms', $terms, $taxonomy, $single);
    $this->$taxonomy = $terms;

    do_action(EBL_PREFIX.'_before_get_terms', $this, $taxonomy, $single);
    if($single == true) $terms = apply_filters(EBL_PREFIX.'_get_single_term', $terms[0]);

    return $terms;
  }

  /**
   * Checks to see if any of the specified values exist
   *
   * @param $args
   *
   * @return bool
   */
  public function hasAny($args){
    if(!is_array($args)) $this->throwWarning('beer04', 'Expected array in hasAny method, '.gettype($args).' was given.');
    foreach($args as $arg){
      if($this->getMetaValue($arg)) return true;
    }

    return false;
  }

  /**
   * Checks to see if all of the specified values exist
   *
   * @param $args
   *
   * @return bool
   */
  public function hasAll($args){
    if(!is_array($args)) $this->throwWarning('beer04', 'Expected array in hasAny method, '.gettype($args).' was given.');
    foreach($args as $arg){
      if($this->getMetaValue($arg)) return false;
    }

    return true;
  }

  /**
   * Gets the beer glass
   * @return bool|string
   */
  public function getGlass(){
    if($this->getGlassShape() == 'bottle') return $this->getBottle();
    if($this->glass instanceof glass) return $this->glass->glass();
    if(in_array($this->getGlassShape(), $this->getGlassShapes())){
      $this->glass = new glass($this, $this->getGlassShape());

      return $this->glass->glass();
    }

    return false;
  }

  /**
   * Gets the beer bottle for the current beer, if it has a label to place
   * @return bool|string
   */
  public function getBottle(){
    if(isset($this->bottle) && $this->bottle instanceof glass) return $this->bottle->glass();
    if($this->getBottomLabel() && $this->getTopLabel()){
      $this->bottle = new glass($this, 'bottle');

      return $this->bottle->glass();
    }

    return false;
  }

  /**
   * Gets the ABV of the current beer
   * @return float
   */
  public function getABV(){
    do_action(EBL_PREFIX.'_before_get_abv', $this);

    return apply_filters(EBL_PREFIX.'_get_abv', (float)$this->getMetaValue('abv').'%', $this);
  }

  /**
   * Gets the IBU of the current beer
   * @return float
   */
  public function getIBU(){
    do_action(EBL_PREFIX.'_before_get_ibu', $this);

    return apply_filters(EBL_PREFIX.'_get_ibu', (float)$this->getMetaValue('ibu'), $this);
  }

  /**
   * Gets the OG of the current beer
   * @return float
   */
  public function getOG(){
    do_action(EBL_PREFIX.'_before_get_og', $this);

    return apply_filters(EBL_PREFIX.'_get_og', (float)$this->getMetaValue('og'), $this);
  }

  /**
   * Gets the Price of the current beer, if the show_price is set to true.
   * @return float
   */
  public function getPrice(){
    do_action(EBL_PREFIX.'_before_get_price', $this);

    return apply_filters(EBL_PREFIX.'_get_price', (float)$this->getMetaValue('price').$this->getOption('currency_symbol'), $this);
  }

  /**
   * Gets the video URL of the current beer
   * @return string
   */
  public function getVideoUrl(){
    do_action(EBL_PREFIX.'_before_get_video_url', $this);

    return apply_filters(EBL_PREFIX.'_get_video_url', (string)$this->getMetaValue('video'), $this);
  }

  /**
   * Gets the video markup of the current video
   * @return string
   */
  public function getVideo(){
    do_action(EBL_PREFIX.'_before_get_video', $this);

    return apply_filters(EBL_PREFIX.'_get_video', '<div class="ebl-beer-video-wrapper">'.wp_oembed_get($this->getVideoUrl()).'</div>', $this);
  }

  /**
   * Gets the gallery of the current beer
   * @return array|string
   */
  public function getGalleryItems($as_string = false){
    do_action(EBL_PREFIX.'_before_process_gallery_items', $this, $as_string);
    $gallery = apply_filters(EBL_PREFIX.'_get_gallery_items_array', $this->getMetaValue('gallery'), $this, $as_string);
    if($as_string){
      $gallery = apply_filters(EBL_PREFIX.'_get_gallery_items_string', implode(',', $gallery), $this, $as_string);
    }
    do_action(EBL_PREFIX.'_before_get_gallery_items', $this, $as_string);

    return $gallery;
  }

  /**
   * Gets the gallery of the current beer
   * @return string
   */
  public function getGallery(){
    do_action(EBL_PREFIX.'_before_get_gallery', $this);

    return apply_filters(EBL_PREFIX.'_get_gallery', do_shortcode("[gallery ids=\"{$this->getGalleryItems(true)}\"]"), $this);
  }

  /**
   * Get the Untappd URL of the current beer
   * @return string
   */
  public function getUntappdURL(){
    do_action(EBL_PREFIX.'_before_get_untappd_url', $this);

    return apply_filters(EBL_PREFIX.'_get_untappd_url', (string)$this->getMetaValue('untappd_url'), $this);
  }

  /**
   * Get the Bottom Label of the current beer
   * @return string
   */
  public function getBottomLabel(){
    do_action(EBL_PREFIX.'_before_get_bottom_label', $this);

    return apply_filters(EBL_PREFIX.'_get_bottom_label', (int)$this->getMetaValue('label'), $this);
  }

  /**
   * Get the Top Label of the current beer
   * @return string
   */
  public function getTopLabel(){
    do_action(EBL_PREFIX.'_before_get_top_label', $this);

    return apply_filters(EBL_PREFIX.'_get_top_label', (int)$this->getMetaValue('top_label'), $this);
  }

  /**
   * Get the end date of the current beer
   * @return string
   */
  public function getAvailabilityEndDate($format = 'F'){
    do_action(EBL_PREFIX.'_before_get_availability_end_date', $this);
    if($this->getAvailabilityStartDate(null) == 0) return false;
    if($format){
      $date = DateTime::createFromFormat('m', $this->getMetaValue('availability_end_date'));
      $date = $date->format($format);
    }
    else{
      $date = $this->getMetaValue('availability_end_date');
    }

    return apply_filters(EBL_PREFIX.'_get_top_availability_end_date', $date, $date, $format, $this);
  }

  /**
   * Get the start date of the current beer
   * @return string
   */
  public function getAvailabilityStartDate($format = 'F'){
    do_action(EBL_PREFIX.'_before_get_availability_start_date', $this);
    $start_date = $this->getMetaValue('availability_start_date');
    //If the availability is 0, it is a year-round beverage.
    if(!$start_date){
      $date = apply_filters(EBL_PREFIX.'_get_top_availability_start_date_year_round', 'Year-Round', $format, $this);
    }
    else{
      if($format){
        $date = DateTime::createFromFormat('m', $start_date);
        $date = $date->format($format);
      }
      else{
        $date = $start_date;
      }
    }

    return apply_filters(EBL_PREFIX.'_get_top_availability_start_date', $date, $date, $format, $this);
  }

  /**
   * Checks to see if the current beer is on-tap
   * @return bool
   */
  public function isOnTap(){
    do_action(EBL_PREFIX.'_before_on_tap', $this);

    return apply_filters(EBL_PREFIX.'_on_tap', (bool)$this->getMetaValue('on_tap'), $this);
  }

  /**
   * Get the SRM value (color) of the current beer
   * Can be returned as a hex value, or as the SRM number (1-40)
   * @return string|int|array
   */
  public function getSRM($format = 'value'){
    do_action(EBL_PREFIX.'_before_process_srm_value', $this, $format);
    $srm = apply_filters(EBL_PREFIX.'_get_srm_value', (int)$this->getMetaValue('srm_value'), $this, $format);
    $srm_array_number = (int)$srm == 0 ? 10 : $srm - 1;
    if($format == 'hex'){
      $srm = apply_filters(EBL_PREFIX.'_get_srm_hex_value', (string)ebl::SRM_VALUES[$srm_array_number], $this, $format);
    }
    elseif($format == 'rgb'){
      $srm = list($r, $g, $b) = sscanf(ebl::SRM_VALUES[$srm_array_number], "#%02x%02x%02x");
      $srm = apply_filters(EBL_PREFIX.'_get_srm_rgb_value', $srm, $this, $format);
    }
    do_action(EBL_PREFIX.'_before_get_srm_value', $this, $format);

    return $srm;
  }

  public function getGlassShape(){
    do_action(EBL_PREFIX.'_before_glass_shape', $this);

    return apply_filters(EBL_PREFIX.'_glass_shape', (string)$this->getMetaValue('glass_shape'), $this);
  }

  /**
   * Gets the Style object
   * @return object
   */
  public function getStyle($format = 'string'){
    do_action(EBL_PREFIX.'_before_style', $this);
    $style = $this->getTerms('style', true);
    if($format == 'string' && $style instanceof \WP_Term){
      $style = $style->name;
    }
    if($format == 'slug' && $style instanceof \WP_Term){
      $style = $style->slug;
    }

    return apply_filters(EBL_PREFIX.'_style', $style, $this);
  }

  /**
   * Gets the Pairing object
   * @return object
   */
  public function getPairings($args = []){
    do_action(EBL_PREFIX.'_before_pairings', $this);

    return apply_filters(EBL_PREFIX.'_pairings', $this->getTerms('pairing', false, $args), $this);
  }

  /**
   * Gets an array of Tag objects
   * @return array
   */
  public function getTags($args = []){
    do_action(EBL_PREFIX.'_before_tags', $this);

    return apply_filters(EBL_PREFIX.'_tags', $this->getTerms('tags', false, $args), $this);
  }

  /**
   * Gets the beer data from the API
   *
   * @param \WP_REST_Request $req
   *
   * @return beer
   */
  public static function getDataFromAPI(\WP_REST_Request $req){
    $id = $req->get_param('id');
    $beer = new beer($id);
    $beer->getMetaValues();

    return $beer;
  }
}