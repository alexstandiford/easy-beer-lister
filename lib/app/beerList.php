<?php
/**
 * Primary class for querying Easy Beer Lister's Beers
 * For the most part, this uses standard WP_Query, but it forces the post type parameter, and adds a few useful hooks and filters
 * @author: Alex Standiford
 * @date  : 10/14/17
 */


namespace ebl\app;

if(!defined('ABSPATH')) exit;

class beerList{

  private $defaults = [];
  public $args;
  public $query;
  public $beer;

  public function __construct($args = []){
    $args['post_type'] = 'beers'; //Force the post type to be beers for this object
    $this->args = wp_parse_args($args, $this->defaults);
    $this->query = new \WP_Query($this->args);
    if($this->query->have_posts()) $this->beer = new beer($this->query->posts[0]);
  }

  public function haveBeers(){
    return $this->query->have_posts();
  }

  public function theBeer(){
    $this->query->the_post();

    return $this->beer = new beer();
  }
}