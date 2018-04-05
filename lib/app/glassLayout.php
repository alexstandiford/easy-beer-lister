<?php
/**
 * Gets, or creates a glass layout.
 * A glass layout is essentially a collection of glasses. This is used to make a glass with a bottle, for example.
 * @author: Alex Standiford
 * @date  : 12/17/17
 */


namespace ebl\app;


use ebl\core\ebl;

if(!defined('ABSPATH')) exit;

class glassLayout extends ebl{

  public $beer;
  public $glass;
  public $layouts = [];
  public $layoutFormats = [];
  public $layoutArgs = [];
  public $layout = '';
  public $defaultLayout = ['srm' => null, 'shape' => null, 'layout' => null];

  public function __construct($beer = null, array $layout_args = []){
    if(is_int($beer)){
      $this->beer = new beer($beer);
    }
    elseif($beer instanceof beer){
      $this->beer = $beer;
    }

    if(isset($layout_args['layout'])) $this->layout = $layout_args['layout'];
    unset($layout_args['layout']);

    if($this->beer instanceof beer){
      $this->defaultLayout = wp_parse_args(['bottom_label_image_id' => $this->beer->getBottomLabel(), 'top_label_image_id' => $this->beer->getTopLabel(), 'srm' => $this->beer->getSRM('value'), 'shape' => $this->getGlassShape()], $this->defaultLayout);
      if(empty($layout_args)){
        $this->layoutArgs = $this->getGlassLayoutValue();
      }
    }
    if(empty($this->layoutArgs)) $this->layoutArgs = $layout_args;
    parent::__construct();
  }

  /**
   * Gets the current glass layout. Creates the layout from specified layout format if a layout wasn't already specified
   * @return bool|array
   */
  public function getGlassLayout($echo = true){
    if($this->hasErrors()) return false;
    foreach($this->layoutArgs as $layout_args){
      $args = wp_parse_args($layout_args, $this->defaultLayout);
      $current_glass = new glass($args);
      $this->layouts[] = $current_glass;
      if($echo) echo $current_glass->glass();
    }

    return $this->layouts;
  }

  /**
   * Gets the glass layout format. Creates one from the database if it isn't already set.
   * @return array|bool
   */
  public function getGlassLayoutFormats(){
    if($this->hasErrors()) return false;
    if(empty($this->layoutFormats)){
      do_action($this->prefix('before_get_glass_data'), $this);
      $layout_array = $this->parseLayoutString();
      $this->layoutFormats = apply_filters($this->prefix('glass_data'), $layout_array, $this);
    }

    return $this->layoutFormats;
  }

  /**
   * Parses a string from the database as an ebl layout array
   * @return array|bool
   */
  public function parseLayoutString(){
    if($this->hasErrors()) return false;
    $layout_array = explode(',', $this->beer->getMetaValue('glass_layout', false, 'shaker,glass-bottle'));
    if($this->layout != '') $layout_array[1] = $this->layout;
    $shape = isset($layout_array[0]) ? $layout_array[0] : null;
    $layout = isset($layout_array[1]) ? explode('-', $layout_array[1]) : null;
    $layout_array = ['shape' => $shape, 'layout' => []];
    if(is_array($layout)){
      foreach($layout as $layout_item){
        if($layout_item == 'glass') $layout_item = $shape;
        $layout_array['layout'][] = ['shape' => $layout_item];
      }
    }

    return $layout_array;
  }

  /**
   * Get the glass shape of the layout
   * @return mixed
   */
  public function getGlassShape(){
    if($this->hasErrors()) return false;
    do_action($this->prefix('before_get_glass_shape'), $this);
    $glass_data = $this->getGlassLayoutFormats();
    $glass_shape = $glass_data['shape'];

    return $glass_shape;
  }

  /**
   * Gets the layout instructions for the glass.
   * @return string|array - layout value of the current glass
   */
  public function getGlassLayoutValue(){
    if($this->hasErrors()) return false;
    do_action($this->prefix('before_get_glass_layout_value'), $this);
    $glass_data = $this->getGlassLayoutFormats();

    return $glass_data['layout'];
  }

  /**
   * Required on every object to check for errors
   * @return mixed
   */
  protected function checkForErrors(){
    if(!$this->beer && empty($this->layout)) return $this->throwError('glassLayout02', 'Glass layout cannot run without specifying either a beer, or a layout to display');

    return null;
  }
}