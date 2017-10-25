<?php
/**
 * Beer Shortcode handler
 * @author: Alex Standiford
 * @date  : 10/23/17
 */


namespace ebl\app\shortcode;


use ebl\app\beerList;
use ebl\app\templateLoader;
use ebl\core\ebl;

if(!defined('ABSPATH')) exit;

class beerShortcode extends ebl{

  public static $instance = 1;
  public $query = [];
  public $atts = [];

  private $shortcodeAtts = [
    'name' => '',
    'text' => '',
    'id'   => '',
  ];

  public function __construct($atts){
    $this->atts = shortcode_atts($this->shortcodeAtts, $atts);
    $this->atts['name'] = strtolower(str_replace('-', ' ', $this->atts['name']));
    self::$instance++;
    $this->query = ['posts_per_page' => 1];
    switch(true){
      case $this->atts['name']:
        $this->query['name'] = $this->atts['name'];
        break;
      case $this->atts['id']:
        $this->query['post__in'] = [(int)$this->atts['id']];
        break;
    }
    parent::__construct();
  }

  /**
   * Displays the text of the shortcode
   * @return mixed|string'
   */
  public function text(){
    return $this->atts['text'] ? $this->atts['text'] : get_the_title();
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
    new beerList($shortcode->query);
    $template = new templateLoader('shortcode', 'beer');
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
    if(!isset($this->atts['name']) && !isset($this->atts['id'])) $this->throwError('beer-shortcode 01', 'The shortcode could not be displayed, you must specify the ID of the beer, or the title of the beer to use this shortcode');

    return;
  }
}