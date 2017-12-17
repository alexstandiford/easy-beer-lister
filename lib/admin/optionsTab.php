<?php
/**
 * Constructor class for a single options page tab
 * @author: Alex Standiford
 * @date  : 11/11/17
 */


namespace ebl\admin;


use ebl\core\ebl;

if(!defined('ABSPATH')) exit;

class optionsTab extends ebl{

  public $slug;
  private static $activeTab = false;

  public function __construct($slug,$title = false){
    $this->slug = sanitize_title_with_dashes($slug);
    $this->title = !$title ? str_replace('-',' ',$this->slug) : $title;
    $this->setActiveTab();
    parent::__construct();
  }

  /**
   * Gets the class of the current slug based on the current active tab
   * @return string
   */
  public function tabClass(){
    $classes = ['nav-tab'];
    if(self::$activeTab == $this->slug) $classes[] = 'nav-tab-active';

    return implode(' ',$classes);
  }

  /**
   * Sets the active tab based on the get param.
   */
  private function setActiveTab(){
    if(self::$activeTab == false){
      self::$activeTab = isset($_GET['tab']) ? $_GET['tab'] : $this->slug;
    }
  }

  /**
   * Required on every object to check for errors
   * @return mixed
   */
  protected function checkForErrors(){
    // TODO: Implement checkForErrors() method.
  }
}