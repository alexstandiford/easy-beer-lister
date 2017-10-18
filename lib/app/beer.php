<?php
/**
 * Constructor for a single beer
 * @author: Alex Standiford
 * @date  : 10/14/17
 */


namespace ebl\app;


use ebl\core\ebl;

if(!defined('ABSPATH')) exit;

class beer extends ebl{

  public $post;


  public function __construct($post = null){
    $this->post = get_post($post);
    parent::__construct();
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
    //TODO: Create Beer Meta Database Entry
    //TODO: Create function that cleans up old database to use new database entry method
    $meta = get_post_meta($this->post->ID, EBL_PREFIX.'_beer_info', true);
    foreach($meta as $key => $meta_value){
      $key = $this->removePrefix($key);
      $this->$key = $meta_value;
    }

    do_action(EBL_PREFIX.'_before_get_meta_value');
    if(!isset($this->$value)) $this->$value = false;

    return apply_filters(EBL_PREFIX.'_get_meta_value', $this->$value);
  }

  /**
   * Get the terms from the specified taxonomy
   *
   * @param      $taxonomy
   * @param bool $single
   *
   * @return array|bool|\WP_Error
   */
  public function getTerms($taxonomy, $single = false){
    if($this->hasErrors()) return false; //Bail early if the object has errors
    if(isset($this->$taxonomy)) return $this->$taxonomy; //Bail early if we already loaded this value in

    //Check to see if the taxonomy exists. if it does, get it. Otherwise, throw an error and bail.
    if(taxonomy_exists($taxonomy)){
      $terms = wp_get_post_terms($this->post->ID, $taxonomy);
    }
    else{
      $this->throwError('beer03', "The taxonomy {$taxonomy} does not exist");

      return false;
    }


    do_action(EBL_PREFIX.'_before_get_terms');

    return $single == true ? apply_filters(EBL_PREFIX.'_get_single_term', $terms) : apply_filters(EBL_PREFIX.'_get_terms');
  }

  /**
   * Gets the ABV of the current beer
   * @return float
   */
  public function getABV(){
    do_action(EBL_PREFIX.'_before_get_abv');

    return apply_filters(EBL_PREFIX.'_get_abv', (float)$this->getMetaValue('abv'));
  }

  /**
   * Gets the IBU of the current beer
   * @return float
   */
  public function getIBU(){
    do_action(EBL_PREFIX.'_before_get_ibu');

    return apply_filters(EBL_PREFIX.'_get_ibu', (float)$this->getMetaValue('ibu'));
  }

  /**
   * Gets the OG of the current beer
   * @return float
   */
  public function getOG(){
    do_action(EBL_PREFIX.'_before_get_og');

    return apply_filters(EBL_PREFIX.'_get_og', (float)$this->getMetaValue('og'));
  }

  /**
   * Gets the Price of the current beer, if the show_price is set to true.
   * @return float
   */
  public function getPrice(){
    do_action(EBL_PREFIX.'_before_get_price');

    return apply_filters(EBL_PREFIX.'_get_price', (float)$this->getOption('show_price') == true ? (float)$this->getMetaValue('price') : null);
  }

  /**
   * Gets the video URL of the current beer
   * @return string
   */
  public function getVideoUrl(){
    do_action(EBL_PREFIX.'_before_get_video_url');

    return apply_filters(EBL_PREFIX.'_get_video_url', (string)$this->getMetaValue('video'));
  }

  /**
   * Gets the video markup of the current video
   * @return string
   */
  public function getVideo(){
    do_action(EBL_PREFIX.'_before_get_video');

    return apply_filters(EBL_PREFIX.'_get_video', do_shortcode("[video src=\"{$this->getVideoUrl()}\"]"));
  }

  /**
   * Gets the gallery of the current beer
   * @return array|string
   */
  public function getGalleryItems($as_string = false){
    do_action(EBL_PREFIX.'_before_process_gallery_items');
    $gallery = apply_filters(EBL_PREFIX.'_get_gallery_items_array', $this->getMetaValue('gallery'));
    if($as_string){
      $gallery = apply_filters(EBL_PREFIX.'_get_gallery_items_string', implode(',', $gallery));
    }
    do_action(EBL_PREFIX.'_before_get_gallery_items');

    return $gallery;
  }

  /**
   * Gets the gallery of the current beer
   * @return string
   */
  public function getGallery(){
    do_action(EBL_PREFIX.'_before_get_gallery');

    return apply_filters(EBL_PREFIX.'_get_gallery', do_shortcode("[gallery ids=\"{$this->getGalleryItems(true)}\"]"));
  }

  /**
   * Get the Untappd URL of the current beer
   * @return string
   */
  public function getUntappdURL(){
    do_action(EBL_PREFIX.'_before_get_untappd_url');

    return apply_filters(EBL_PREFIX.'_get_untappd_url', (string)$this->getMetaValue('untappd_url'));
  }

  /**
   * Checks to see if the current beer is on-tap
   * @return bool
   */
  public function isOnTap(){
    do_action(EBL_PREFIX.'_before_on_tap');

    return apply_filters(EBL_PREFIX.'_on_tap', (bool)$this->getMetaValue('is_on_tap'));
  }

  /**
   * Get the SRM value (color) of the current beer
   * Can be returned as a hex value, or as the SRM number (1-40)
   * @return string|int|array
   */
  public function getSRM($format = 'value'){
    do_action(EBL_PREFIX.'_before_process_srm_value');
    $srm = apply_filters(EBL_PREFIX.'_get_srm_value', (int)$this->getMetaValue('srm_value'));

    if($format == 'hex'){
      $srm = apply_filters(EBL_PREFIX.'_get_srm_hex_value', (string)ebl::SRM_VALUES[$srm - 1]);
    }
    elseif($format == 'rgb'){
      $srm = list($r, $g, $b) = sscanf(ebl::SRM_VALUES[$srm - 1], "#%02x%02x%02x");
      $srm = apply_filters(EBL_PREFIX.'_get_srm_rgb_value', $srm);
    }
    do_action(EBL_PREFIX.'_before_get_srm_value');

    return $srm;
  }

  public function getGlassShape(){
    do_action(EBL_PREFIX.'_before_glass_shape');

    return apply_filters(EBL_PREFIX.'_glass_shape', (string)$this->getMetaValue('glass_shape'));
  }

  /**
   * Gets the Style object
   * @return object
   */
  public function getStyle(){
    do_action(EBL_PREFIX.'_before_style');

    return apply_filters(EBL_PREFIX.'_style', $this->getTerms('style', true));
  }

  /**
   * Gets the Pairing object
   * @return object
   */
  public function getPairing(){
    do_action(EBL_PREFIX.'_before_pairing');

    return apply_filters(EBL_PREFIX.'_pairing', $this->getTerms('pairing', true));
  }

  /**
   * Gets the Availability object
   * @return object
   */
  public function getAvailability(){
    do_action(EBL_PREFIX.'_before_availability');

    return apply_filters(EBL_PREFIX.'_availability', $this->getTerms('availability', true));
  }

  /**
   * Gets an array of Tag objects
   * @return array
   */
  public function getTags(){
    do_action(EBL_PREFIX.'_before_tags');

    return apply_filters(EBL_PREFIX.'_tags', $this->getTerms('tags'));
  }
}