<?php
/**
 * Core Easy Beer Lister Class. Contains Error Handlers, and other helper functions for other child classes
 * @author: Alex Standiford
 * @date  : 10/14/17
 */


namespace ebl\core;


if(!defined('ABSPATH')) exit;

class ebl{

  public function __construct(){
    $this->errors = [];
  }

  /**
   * Adds an error to the array of errors, and triggers a PHP warning
   *
   * @param string|int $code Error code
   * @param string $message Error message
   * @param mixed $data Optional. Error data
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
    return empty($this->errors);
  }

  /**
   * Gets an option using wp_option. Applies the EBL prefix automatically
   * @param      $option
   * @param bool $default
   *
   * @return mixed
   */
  public function getOption($option, $default = false){
    $option = EBL_PREFIX.$option;
    return get_option($option,$default);
  }

}