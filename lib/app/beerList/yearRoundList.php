<?php
/**
 * Quick way to get a list of the beers that are in-season
 * @author: Alex Standiford
 * @date  : 10/21/17
 */

namespace ebl\app\beerList;

use ebl\app\beerList;

if(!defined('ABSPATH')) exit;

class yearRoundList extends beerList{

  public function __construct($args = []){

    $current_month = (int)date('n');
    $defaults = [
      'meta_query'     => [
        'relation' => 'AND',
        [
          'relation' => 'OR',
          [
            'key'   => $this->prefix('availability_start_date'),
            'value' => 0,
          ],
          [
            'key'     => $this->prefix('availability_start_date'),
            'compare' => 'NOT EXISTS',
          ],
        ],
      ],
      'posts_per_page' => -1,
    ];
    $this->month = $current_month;

    $args = wp_parse_args($args, $defaults);
    parent::__construct($args);
  }

  public static function getDataFromAPI(\WP_REST_Request $req, $self = null){
    $self = new self();

    return parent::getDataFromAPI($req, $self);
  }
}