<?php
/**
 * Primary class for querying Easy Beer Lister's Beers
 * For the most part, this uses standard WP_Query, but it forces the post type parameter, and adds a few useful hooks and filters
 * This class can be easily extended if you want to make your own query to call over, and over again.
 * For examples, see /lib/app/beerList/*.php
 * @author: Alex Standiford
 * @date  : 10/14/17
 */


namespace ebl\app;

use ebl\core\ebl;

if(!defined('ABSPATH')) exit;

class beerList extends ebl{

  private $defaults = ['fields' => 'ids', 'posts_per_page' => -1];
  public $args;
  public $query;
  public $beer;
  public $similar;
  public $beerToCompare;
  public $hasRanSimilar = false;

  public function __construct($args = []){
    $this->defaults['include_unavailable'] = $this->getOption('default_behavior_for_unavailable_beers', 0) == 1 ? false : true;
    $this->args = $args;
    $this->theArgs = 4;
    parent::__construct();
    if(!$this->hasErrors()){
      if($this->args instanceof \WP_Query){
        $this->query = $this->args;
        $this->args = $this->query->args;
      }
      else{
        if(isset($this->args['post_type']) && $this->args['post_type'] !== 'beers') $this->throwError('beerlist02', 'The post type was set to '.$this->args['post_type'].' instead of "beers". This was automatically overwritten, but you should remove this specification to prevent potential issues in the future.');
        $this->args['post_type'] = 'beers'; //Force the post type to be beers for this object

        $this->constructMetaQuery(); //Construct the meta query based on custom options
        $this->args = wp_parse_args($this->args, $this->defaults);
        $this->query = new \WP_Query($this->args);
      }
      if($this->query->have_posts()){
        $this->beer = new beer($this->query->posts[0]);
        $this->beerToCompare = $this->beer; //for similar beers
      }
      $GLOBALS[EBL_PREFIX.'_beer_list'] = $this;
      $this->similar = $this;
    }
  }

  /**
   * Equivalent to WP_Query::have_posts()
   */
  public function haveBeers(){
    $result = false;
    if(!$this->hasErrors()){
      if($this->query->have_posts()){
        $result = true;
      }
      else{
        wp_reset_query();
      }
    }

    return $result;
  }

  /**
   * Equivalent to WP_Query::the_post()
   * @return bool|beer
   */
  public function theBeer(){
    if(!$this->hasErrors()){
      $this->query->the_post();
      $this->beer = new beer();
      $GLOBALS[EBL_PREFIX.'_beer'] = $this->beer;
      $GLOBALS[EBL_PREFIX.'_beer_list'] = $this;

      return $this->beer;
    }

    return false;
  }

  /**
   * Constructs the meta query, and sets based on some of the custom args that can be passed into this object
   */
  private function constructMetaQuery(){
    if(!isset($this->args['include_unavailable'])) $this->args['include_unavailable'] = $this->getOption('default_behavior_for_unavailable_beers', 0) == 1 ? false : true;
    if(!$this->args['include_unavailable'] && $this->args['meta_query']['relation'] != 'OR'){
      if(!isset($this->args['meta_query'])) $this->args['meta_query'] = [];
      $this->args['meta_query'][] = [
        'relation' => 'OR',
        [
          'key'     => $this->prefix('availability_start_date'),
          'value'   => -1,
          'compare' => 'NOT IN',
        ],
        [
          'key'     => $this->prefix('availability_start_date'),
          'compare' => 'NOT EXISTS',
        ],
      ];
      unset($this->args['include_unavailable']);
    }
  }

  /**
   * Required on every object to check for errors
   * @return mixed
   */
  protected function checkForErrors(){
    if(!is_array($this->args) && !$this->args instanceof \WP_Query){
      $this->throwError('beerlist01', 'The specified arguments array must be an array, or a WP_Query object. '.gettype($this->args).' given.');
    }

    return null;
  }

  /**
   * Loads up similar beers to query and loop through
   *
   * @param array $args
   */
  public function getSimilarBeers($args = []){
    if($this->hasErrors()) return false;
    if($this->hasRanSimilar) return $this->similar;
    if($this->beerToCompare instanceof beer){
      $slug = $this->beerToCompare->getStyle('object');
      $slug = isset($slug->slug) ? $slug->slug : '';
      $default_args = [
        'tax_query'      => [
          'relation' => 'OR',
          [
            'taxonomy' => 'style',
            'field'    => 'slug',
            'terms'    => $slug,
          ],
          [
            'taxonomy' => 'tags',
            'field'    => 'term_id',
            'terms'    => $this->beerToCompare->getTags(['fields' => 'ids']),
            'relation' => 'OR',
          ],
        ],
        'orderby'        => 'rand',
        'post__not_in'   => [$this->beerToCompare->post->ID],
        'posts_per_page' => 4,
      ];

      $args = wp_parse_args($args,$default_args);
      $this->hasRanSimilar = true;
      $this->similar = new self($args);
    }

    return $this->similar;
  }

  public function getBeerList(){

  }

  /**
   * Gets a beer list from the API
   */
  public static function getDataFromAPI(\WP_REST_Request $req, $self = null){
    $params = $req->get_params();
    if(!isset($self)) $self = new self($params);
    $self->isApi = true;
    $results = [];
    if($self->haveBeers()){
      while($self->haveBeers()){
        $self->theBeer();
        $self->beer->getMetaValues();
        $results[] = $self->beer;
      }
    }

    return $self->apiReturn($results);
  }
}