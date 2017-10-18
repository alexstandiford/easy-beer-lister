<?php
/**
 * Core Easy Beer Lister Class. Contains Error Handlers, and other helper functions for other child classes
 * @author: Alex Standiford
 * @date  : 10/14/17
 */


namespace ebl\core;


if(!defined('ABSPATH')) exit;

abstract class ebl{

  const SRM_VALUES = ['#FCEABC', '#F9DB99', '#F6CC76', '#F1BB57', '#ECAC3C', '#E79C23', '#DF8D05', '#D87F06', '#CE7304', '#CA6704', '#C05C04', '#B75103', '#AF4A03', '#A84003', '#A03903', '#983203', '#902B05', '#892502', '#851F02', '#7B1B02', '#761605', '#6F1102', '#6A0D02', '#630904', '#5E0001', '#590001', '#540001', '#500001', '#4C0001', '#470001', '#450001', '#410004', '#3D0001', '#390001', '#360004', '#330001', '#300001', '#2E0401', '#2C0004', '#200004'];

  public function __construct(){
    $this->errors = [];
    $this->checkForErrors();
  }

  /**
   * Adds an error to the array of errors, and triggers a PHP warning
   *
   * @param string|int $code    Error code
   * @param string     $message Error message
   * @param mixed      $data    Optional. Error data
   *
   * @return \WP_Error
   */
  public function throwError($code_or_error, $message = '', $data = null){
    $error = null;

    //If the error code is a string or int, use it
    if(is_string($code_or_error) || is_int($code_or_error)){
      $error = new \WP_Error($code_or_error, __($message), $data);
      trigger_error(__($message));
    }
    //If the error code isn't a string, check to see if it's a WP error. If it is, use that.
    elseif(is_wp_error($code_or_error)){
      $error = $code_or_error;
    }

    $this->errors[] = $error;

    return $error;
  }

  /**
   * Checks to see if the current object has any errors
   * @return bool
   */
  public function hasErrors(){
    return !empty($this->errors);
  }

  /**
   * Checks to see if a specified file exists. Throws an error if not
   *
   * @param $file
   *
   * @return bool
   */
  public function fileExists($file){
    if(!file_exists($file)){
      $this->throwError('missing_file', 'The file at '.$file.'. does not exist.');

      return false;
    }

    return true;
  }

  /**
   * Gets an option using wp_option. Applies the EBL prefix automatically
   *
   * @param      $option
   * @param bool $default
   *
   * @return mixed
   */
  public function getOption($option, $default = false){
    $option = EBL_PREFIX.$option;

    return get_option($option, $default);
  }

  public function getGlassShapes(){
    $glass_shapes = [
      'tulip', 'snifter', 'hefeweizen', 'mug', 'shaker', 'bottle',
    ];

    return apply_filters('ebl_glass_shapes', $glass_shapes);
  }

  /**
   * Gets rid of the ebl prefix on a key
   *
   * @param $value
   *
   * @return bool|string
   */
  public function removePrefix($value){
    if(strpos($value, EBL_PREFIX.'_') !== false){
      $value = (substr($value, strlen(EBL_PREFIX.'_')));
    }

    return $value;
  }

  /**
   * Required on every object to check for errors
   * @return mixed
   */
  abstract protected function checkForErrors();

}