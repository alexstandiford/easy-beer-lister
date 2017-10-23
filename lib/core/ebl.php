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
  public $isApi = false;

  public function __construct(){
    $this->errors = [];
    $this->checkForErrors();
    $this->enqueue();
    do_action(EBL_PREFIX.'_after_ebl', $this);
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
    if($this->isApi){
      $data = ['status' => 400];
    }

    //If the error code is a string or int, use it
    if(is_string($code_or_error) || is_int($code_or_error)){
      $error = new \WP_Error($code_or_error, __($message), $data);
      $this->throwWarning($code_or_error, $message);
    }
    //If the error code isn't a string, check to see if it's a WP error. If it is, use that.
    elseif(is_wp_error($code_or_error)){
      $error = $code_or_error;
    }

    $this->errors[] = $error;

    return $error;
  }

  /**
   * Used to return a result for API endpoints. Handles proper error returning when there are any errors
   * @param $result
   *
   * @return array|\WP_Error
   */
  public function apiReturn($result){
    if($this->isApi != true) $this->throwError('ebl01', 'The method apiReturn is designed to be used with API Endpoints. Be sure to specify isApi to true in your object');
    if($this->hasErrors()){
      $result = [];
      foreach ($this->errors as $error){
        $result[key($error->errors)] = $error->errors[key($error->errors)];
      }
    }

    return $this->hasErrors() ? new \WP_Error('ebl01','Errors found',['errors' => $result,'status' => 400]) : $result;
  }

  /**
   * Enqueues styles and scripts on the first instance of ebl
   */
  private function enqueue(){
    if(!wp_style_is('ebl-style', 'enqueued')){
      wp_enqueue_style('ebl-style', EBL_ASSETS_URL.'css/ebl.css', [], EBL_VERSION);
      wp_enqueue_script('ebl-script',EBL_ASSETS_URL.'js/ebl-script.js',['jquery','ebl'],EBL_VERSION);
    }
  }

  /**
   * Throws a warning, but does not stop the object from running
   *
   * @param        $code_or_error
   * @param string $message
   */
  public function throwWarning($code_or_error, $message = ''){
    trigger_error(__($code_or_error.' error: '.$message));
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
   * Dumps errors in the current object
   */
  public function dumpErrors(){
    foreach($this->errors as $error){
      var_dump($error);
    }
  }

  /**
   * Required on every object to check for errors
   * @return mixed
   */
  abstract protected function checkForErrors();

}