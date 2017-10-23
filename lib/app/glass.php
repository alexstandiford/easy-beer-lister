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
  public $srm = 8;

  public function __construct($beer = null, $shape = null){
    if(isset($shape)){
      $this->glassShape = $shape;
    }
    if(isset($beer)){
      if(is_int($beer)) $beer = new beer($beer);
      $this->beer = $beer;
    }
    parent::__construct();
    if(!$this->hasErrors()){
      if(isset($beer)){
        $this->srm = $this->beer->getSRM() == 0 ? $this->srm : $this->beer->getSRM();
        $this->srmHex = $this->beer->getSRM('hex');
        $this->srmRGB = $this->beer->getSRM('rgb');
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
   *
   * @param array $srm_classes
   *
   * @return bool|string
   */
  public function glass(){
    $this->getBeerGlassSVG();
    if($this->srm && $this->getGlassShape()){
      ob_start();
      $file = EBL_ASSETS_PATH.'svg/glass.php';
      if($this->fileExists($file)) include($file);
      $svg = ob_get_clean();
    }
    else{
      $svg = false;
    }

    return $svg;
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
    if(!isset ($this->beer)) $this->throwError('glass03', 'Get Label requires the beer object. Be sure to include a $beer post or ID when using the glass class');
    $label = $position == 'bottom' ? $this->beer->getBottomLabel() : $this->beer->getTopLabel();
    $size = $position == 'bottom' ? EBL_PREFIX.'_bottom_label' : EBL_PREFIX.'_top_label';

    return wp_get_attachment_image_url($label, $size);
  }

}