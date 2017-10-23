<?php
/**
 * Quick way to get a list of the beers that are on tap
 * @author: Alex Standiford
 * @date  : 10/21/17
 */

namespace ebl\app\beerList;

use ebl\app\beerList;

if(!defined('ABSPATH')) exit;

class tapList extends beerList{

  public function __construct($args = []){
    $defaults = [
      'meta_query'     => [
        [
          'key'   => EBL_PREFIX.'_on_tap',
          'value' => 1,
        ],
      ],
      'posts_per_page' => -1,
    ];

    $args = wp_parse_args($args, $defaults);
    parent::__construct($args);
  }

  public static function getDataFromAPI(\WP_REST_Request $req,$self = null){
      $self = new self();
      return parent::getDataFromAPI($req,$self);
  }
}