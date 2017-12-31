<?php
/**
 * Quick way to get a list of the beers that are in-season
 * @author: Alex Standiford
 * @date  : 10/21/17
 */

namespace ebl\app\beerList;

use ebl\app\beerList;

if(!defined('ABSPATH')) exit;

class outOfSeasonList extends beerList{

  public function __construct($args = []){
    $current_month = (int)date('n');
    $defaults = [
      'meta_query'     => [
        'relation' => 'AND',
        [
          'key'     => $this->prefix('availability_start_date'),
          'value'   => 0,
          'compare' => '!=',
        ],
        [
          'relation' => 'OR',
          [
            'key'     => $this->prefix('availability_start_date'),
            'value'   => $current_month,
            'type'    => 'numeric',
            'compare' => '>=',
          ],
          [
            'key'     => $this->prefix('availability_end_date'),
            'value'   => $current_month,
            'type'    => 'numeric',
            'compare' => '<=',
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