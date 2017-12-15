<?php
/**
 * $FILE_DESCRIPTION$
 * @author: Alex Standiford
 * @date  : 12/15/17
 */


namespace ebl\core;


if(!defined('ABSPATH')) exit;

class option extends ebl{

  public $optionValue;

  public function __construct($option_value){
    $this->optionValue = $this->getOption($option_value);
    parent::__construct();

  }

  public static function get($option_value){
    $self = new self($option_value);
    if($self->hasErrors()) return false;
    return $self->optionValue;
  }

  /**
   * Required on every object to check for errors
   * @return mixed
   */
  protected function checkForErrors(){
    return null;
  }
}