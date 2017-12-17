<?php
/**
 * Gets, or creates a glass layout
 * @author: Alex Standiford
 * @date  : 12/17/17
 */


namespace ebl\app;


use ebl\core\ebl;

if(!defined('ABSPATH')) exit;

class glassLayout extends ebl{

  public $beer;
  public $layoutFormat;
  public $glass;
  public $layout = [];
  public $defaults = [];

  public function __construct($beer = null, array $layout = []){
    if(is_int($beer)){
      $this->beer = new beer($beer);
    }
    elseif($beer instanceof beer){
      $this->beer = $beer;
    }
    if(isset($layout[0]) && $layout[0] instanceof glass){
      $this->layout = $layout;
    }
    else{
      $this->layoutFormat = $layout;
    }
    parent::__construct();
  }

  /**
   * Gets the current glass layout. Creates the layout from specified layout format if a layout wasn't already specified
   * @return bool|array
   */
  public function getGlassLayout($echo = true){
    if($this->hasErrors()) return false;
    if(empty($this->layout)){
      foreach($this->getGlassLayoutValue() as $shape){
        if($shape == 'glass'){
          $current_glass = new glass($this->beer, $this->getGlassShape());
        }
        else{
          $current_glass = new glass($this->beer, $shape);
        }
        $this->layout[] = $current_glass;
        if($echo) echo $current_glass->glass();
      }
    }
    elseif($echo == true){
      foreach($this->layout as $layout){
        echo $layout->glass();
      }
    }

    return $this->layout;
  }

  /**
   * Gets the glass layout format. Creates one from the database if it isn't already set.
   * @return array|bool
   */
  public function getGlassLayoutFormat(){
    if($this->hasErrors()) return false;
    if(empty($this->layoutFormat)){
      do_action($this->prefix('before_get_glass_data'), $this);
      $layout_array = $this->parseLayoutString();
      $this->layoutFormat = apply_filters($this->prefix('glass_data'), $layout_array, $this);
    }

    return $this->layoutFormat;
  }

  /**
   * Parses a string from the database as an ebl layout array
   * @return array|bool
   */
  public function parseLayoutString(){
    if($this->hasErrors()) return false;
    $default_layout = $this->getDefaultGlassLayout();
    $layout_array = explode(',', $this->beer->getMetaValue('glass_layout'));
    $shape = isset($layout_array[0]) ? $layout_array[0] : $default_layout[0];
    $layout = isset($layout_array[1]) ? $layout_array[1] : $default_layout[1];
    $layout_array = ['shape' => $shape, 'layout' => explode('-', $layout)];

    return $layout_array;
  }


  /**
   * Gets the default glass layout when there isn't one to set.
   * @return string
   */
  public function getDefaultGlassLayout(){
    $glass_layout = apply_filters($this->prefix('set_default_glass_layout_value'), ['shaker', 'glass'], $this);

    return $glass_layout;
  }


  /**
   * Get the glass shape of the layout
   * @return mixed
   */
  public function getGlassShape(){
    if($this->hasErrors()) return false;
    do_action($this->prefix('before_get_glass_shape'), $this);
    $glass_data = $this->getGlassLayoutFormat();
    $glass_shape = $glass_data['shape'];
    if(!in_array($glass_shape, $this->getGlassShapes())) return $this->throwError('glassLayout01', 'The specified glass shape '.$glass_shape.' is nnot one of the supported glass shapes. Supported shapes are '.implode(',', $this->getGlassShapes()).'.');

    return $glass_shape;
  }

  /**
   * Gets the layout instructions for the glass.
   * @return string|array - layout value of the current glass
   */
  public function getGlassLayoutValue(){
    if($this->hasErrors()) return false;
    do_action($this->prefix('before_get_glass_layout_value'), $this);
    $glass_data = $this->getGlassLayoutFormat();

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