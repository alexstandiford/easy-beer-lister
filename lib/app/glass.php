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
  public $srmValue = 8;

  public function __construct($beer = null, $shape = null){
    if(isset($shape)){
      $this->glassShape = $shape;
    }
    if(isset($beer)){
      if(is_int($beer)){
        $this->beer = new beer($beer);
      }
      else{
        $this->beer = $beer;
      }
    }
    parent::__construct();
    if(!$this->hasErrors()){
      if(isset($beer)){
        $this->srm = $this->beer->getSRM() == 0 ? $this->srmValue : $this->beer->getSRM();
        $this->srmHex = $this->beer->getSRM('hex');
        $this->srmRGB = $this->beer->getSRM('rgb');
      }
      else{
        $this->srm = $this->getSrmValue('value', $this->srmValue);
        $this->srmHex = $this->getSrmValue('hex', $this->srmValue);
        $this->srmRGB = $this->getSrmValue('rgb', $this->srmValue);
      }
    }
  }

  /**
   * Check for Errors
   */
  function checkForErrors(){
    if(!in_array($this->getGlassShape(), $this->getGlassShapes())){
      $glass_shapes = implode(',', $this->getGlassShapes());
      $this->throwError('glass01', 'The glass shape '.$this->getGlassShape().' is not a valid glass shape. Valid Shapes are: '.$glass_shapes);
    }
    if(isset($this->beer)){
      if(!$this->beer instanceof \ebl\app\beer) $this->throwError('glass02', 'The specified beer is not a valid beer object. Please pass a valid beer object, or a beer object ID');
    }

  }

  /**
   * Grabs the beer glass SVG and adds it inside the loop
   * @return bool|string
   * @internal param array $srm_classes
   */
  public function glass(){
    $this->getBeerGlassSVG();
    if($this->srm && $this->getGlassShape()){
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
    if($this->getGlassShape() != 'bottle'){
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
    ob_start();
    include($file);
    echo ob_get_clean();
  }

  /**
   * Gets the glass shape
   * @return string
   */
  public function getGlassShape(){
    if(!$this->glassShape){
      if(!$this->beer instanceof beer) return $this->throwError('glass03','getGlassShape requires that a glass shape or post id is specified when the class is instantiated');
      $this->glassShape = $this->beer->getGlassShape();
    }
    return $this->glassShape;
  }

  /**
   * Gets the label of the beer
   *
   * @param string $position
   *
   * @return false|string
   */
  public function getLabel($position = 'bottom'){
    if(isset ($this->beer)){
      $label = $position == 'bottom' ? $this->beer->getBottomLabel() : $this->beer->getTopLabel();
    }
    else{
      $label = $position == 'bottom' ? $this->getOption('default_bottom_beer_label') : $this->getOption('default_top_beer_label');
    }
    $size = $position == 'bottom' ? $this->prefix('bottom_label') : $this->prefix('top_label');

    return wp_get_attachment_image_url($label, $size);
  }

}