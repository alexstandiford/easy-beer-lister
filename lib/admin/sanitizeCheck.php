<?php
/**
 * Handles default sanitizations for EBL fields
 * @author: Alex Standiford
 * @date  : 12/12/17
 */


namespace ebl\admin;

use ebl\app\glass;
use ebl\core\ebl;

if(!defined('ABSPATH')) exit;

class sanitizeCheck extends ebl{

  private $sanitized;
  private $field;
  private $value;
  private $postId;

  public function __construct(field $field, $item_to_check_against, $post_id = null){
    $this->field = $field;
    $this->value = $item_to_check_against;
    $this->postId = $post_id;
    parent::__construct();
    $this->sanitized = apply_filters($this->prefix('sanitize_'.$field->type), null, $field);
  }

  /**
   * Runs the sanitization check of the current field, if it hasn't already been run
   *
   * @param $field_type
   */
  public static function sanitize(field $field, $item_to_check_against, $post_id = null){
    $self = new self($field, $item_to_check_against,$post_id);
    if(!$self->hasOverride()){
      switch($field->type){
        case 'glasslayout':
          $self->sanitized = $self->checkGlassLayout();
          break;
        default:
          $self->sanitized = true;
      }
    }

    return $self->sanitized;
  }

  private function checkGlassLayout(){
    $sanitized = true;
    $this->value = explode(',',$this->value);
    $layout = $this->value[1];
    $shape = $this->value[0];

    if(!in_array($shape,$this->getGlassShapes())){
      $sanitized = false;
    }

    if(!in_array($layout,$this->getGlassLayouts())){
      $sanitized = false;
    }

    return $sanitized;
  }

  /**
   * Checks to see if the current item has an override via apply filters
   * @return bool
   */
  public function hasOverride(){
    return $this->sanitized !== null;
  }

  /**
   * Required on every object to check for errors
   * @return mixed
   */
  protected function checkForErrors(){
    return null;
  }
}