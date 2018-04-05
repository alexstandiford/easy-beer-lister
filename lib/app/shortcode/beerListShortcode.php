<?php
/**
 * $FILE_DESCRIPTION$
 * @author: Alex Standiford
 * @date  : 10/24/17
 */


namespace ebl\app\shortcode;


use ebl\app\beerList;
use ebl\app\beerList\tapList;
use ebl\app\beerList\inSeasonList;
use ebl\app\beerList\outOfSeasonList;
use ebl\app\templateLoader;
use ebl\core\ebl;

if(!defined('ABSPATH')) exit;

class beerListShortcode extends ebl{

  public static $instance = 1;
  public $query = [
    'posts_per_page' => -1,
    'fields'         => 'objects',
  ];
  public $atts = [];

  private $shortcodeAtts = [
    'sort'             => 'desc',
    'style'            => null,
    'on-tap'           => null,
    'in-season'        => null,
    'out-of-season'    => null,
    'pairings'         => null,
    'tags'             => null,
    'availability'     => null,
    'show_description' => false,
    'show_price'       => false,
  ];


  public function __construct($atts){
    $this->atts = shortcode_atts($this->shortcodeAtts, $atts);
    self::$instance++;
    $this->buildQuery();
    parent::__construct();
  }

  private function parseTax($tax){
    $this->atts[$tax] = str_replace(' ', '-', $this->atts[$tax]);
    $this->atts[$tax] = strtolower($this->atts[$tax]);
    $this->atts[$tax] = str_getcsv($this->atts[$tax]);

    return $this->atts[$tax];
  }

  /**
   * Generates the appropriate query based on the shortcode atts
   */
  private function getObject(){
    switch(true){
      case (bool)$this->atts['on-tap']:
        new tapList($this->query);
        break;
      case (bool)$this->atts['in-season']:
        new inSeasonList($this->query);
        break;
      case (bool)$this->atts['out-of-season']:
        new outOfSeasonList($this->query);
        break;
      default:
        new beerList($this->query);
    }

    return;
  }

  public function buildQuery(){
    //--- Pairings ---//
    if($this->atts['pairings'] != null){
      $this->query['tax_query'][] = [
        'taxonomy' => 'pairing',
        'field'    => 'slug',
        'terms'    => $this->parseTax('pairings'),
      ];
    };
    //--- Tags ---//
    if($this->atts['tags'] != null){
      $this->query['tax_query'][] = [
        'taxonomy' => 'tags',
        'field'    => 'slug',
        'terms'    => $this->parseTax('tags'),
      ];
    };
    //--- type ---//
    if($this->atts['style'] != null){
      $this->query['tax_query'][] = [
        'taxonomy' => 'style',
        'field'    => 'slug',
        'terms'    => $this->parseTax('style'),
      ];
    };
    $this->query['orderby'] = $this->atts['sort'];
  }

  /**
   * Gets the shortcode
   *
   * @param $atts
   *
   * @return string
   */
  public static function get($atts){
    $shortcode = new self($atts);
    $shortcode->getObject();
    $template = new templateLoader('shortcode', 'beers');
    $template->shortcode = $shortcode;
    ob_start();
    $template->loadTemplate();

    return ob_get_clean();
  }

  /**
   * Required on every object to check for errors
   * @return mixed
   */
  protected function checkForErrors(){
    return;
  }
}
