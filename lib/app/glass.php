<?php
/**
 * Grabs a single glass from the specified post meta
 * @author: Alex Standiford
 * @date  : 10/17/17
 */


namespace ebl\app;


use ebl\core\ebl;

if(!defined('ABSPATH')) exit;

class glass extends ebl{

  public $beer;
  public $glassShape;
  public $srmRGB;
  public $srmHex;
  public $srm;
  public $args;
  public $srmValue = 8;

  public function __construct($args = []){
    $defaults = apply_filters($this->prefix('default_glass_args'), ['srm' => 10, 'shape' => 'shaker'],$this,$args);
    $this->args = wp_parse_args($args, $defaults);
    parent::__construct();
    if(!$this->hasErrors()){
      $this->glassShape = $this->args['shape'];
      $this->srm = $this->getSrmValue('value', $this->args['srm']);
      $this->srmHex = $this->getSrmValue('hex', $this->srm);
      $this->srmRGB = $this->getSrmValue('rgb', $this->srm);
    }
  }

  /**
   * Check for Errors
   */
  function checkForErrors(){
    if(!in_array($this->args['shape'], $this->getGlassShapes())){
      $glass_shapes = implode(',', $this->getGlassShapes());
      $this->throwError('glass01', 'The glass shape '.$this->glassShape.' is not a valid glass shape. Valid Shapes are: '.$glass_shapes);
    }

  }

  /**
   * Grabs the beer glass SVG and adds it inside the loop
   * @return bool|string
   * @internal param array $srm_classes
   */
  public function glass(){
    $this->getBeerGlassSVG();
    if($this->srm && $this->glassShape){
      $svg = $this->getGlass();
    }
    else{
      $svg = false;
    }

    return $svg;
  }

  /**
   * Gets the glass, or bottle shape, based on the input glass shape
   * @return string
   */
  private function getGlass(){
    ob_start();
    if($this->glassShape != 'bottle'){
      $file = EBL_ASSETS_PATH.'svg/glass.php';
    }
    else{
      $file = EBL_ASSETS_PATH.'svg/bottle.php';
    }
    if($this->fileExists($file)) include($file);

    return ob_get_clean();
  }

  public function getViewbox(){
    switch($this->glassShape){
      case "tulip":
        $viewbox = '0 0 275 473.4';
        break;
      case "snifter":
        $viewbox = '0 0 271.7 407.9';
        break;
      case "hefeweizen":
        $viewbox = '0 0 228.4 632.5';
        break;
      case "mug":
        $viewbox = '0 0 410 536.5';
        break;
      case "shaker":
        $viewbox = '0 0 370.255 633.189';
        break;
      default:
        $viewbox = '0 0 162.811 631.616';
        break;
    }

    return $viewbox;
  }

  /**
   * Gets the beer glass SVG content
   */
  private function getBeerGlassSVG(){
    $file = EBL_ASSETS_PATH.'svg/glasses.php';
    include_once($file);
  }

  /**
   * Gets the label of the beer
   *
   * @param string $position
   *
   * @return false|string
   */
  public function getLabel($position = 'bottom'){
    if(isset ($this->args[$position.'_label_image_id'])){
      $label = $this->args[$position.'_label_image_id'];
    }
    else{
      $label = $position == 'bottom' ? $this->getOption('default_bottom_beer_label') : $this->getOption('default_top_beer_label');
    }
    $size = $position == 'bottom' ? $this->prefix('bottom_label') : $this->prefix('top_label');

    return wp_get_attachment_image_url($label, $size);
  }

}