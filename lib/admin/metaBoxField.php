<?php
/**
 * A single field to display inside a meta box
 * @author: Alex Standiford
 * @date  : 10/16/17
 */


namespace ebl\admin;

use ebl\core\ebl;

if(!defined('ABSPATH')) exit;

class metaBoxField extends ebl{

  public $args = [];
  public $inputArgs = [];
  public $metaValue;
  public $id;
  public $description;
  public $class;
  public $name;
  public $previewTarget;
  public $inputTarget;
  public $jsArgs = [];
  public static $fieldID = 0;
  public $selectOptions = [];
  public $type = 'input';
  const EBL_SUPPORTED_META_FIELDS = [
    'input',
    'gallery',
    'srmpicker',
    'select',
    'glassshape',
    'imageupload',
  ];

  public function __construct($args = []){
    if(isset($args['name'])) $this->args = $args;
    parent::__construct();
    if(!$this->hasErrors()){
      self::$fieldID++;

      //Generate field data
      $html_friendly_name = EBL_PREFIX.'-'.sanitize_title($args['name']);

      $default_args = ['preview_target' => 'image-previews-'.self::$fieldID, 'input_target' => 'upload-image-input-'.self::$fieldID, 'input_args' => [], 'description' => '', 'id' => $html_friendly_name, 'meta_value' => $html_friendly_name, 'class' => 'ebl-field'];
      $this->args = wp_parse_args($args, $default_args);

      $this->description = __($this->args['description']);
      $this->class = $this->args['class'];
      $this->name = __($this->args['name']);
      $this->id = $this->args['id'];
      $this->inputArgs = $this->args['input_args'];
      $this->metaValue = $this->args['meta_value'];
      $this->type = isset($this->args['type']) ? $this->args['type'] : $this->type;

      if($this->type == 'gallery' || $this->type == 'imageupload'){
        $this->previewTarget = $this->args['preview_target'];
      }

      if($this->type == 'gallery' || $this->type == 'srmpicker' || $this->type == 'glassshape' || $this->type == 'imageupload'){
        $this->inputTarget = $this->args['input_target'];
      }

      if($this->type == 'select'){
        $this->selectOptions = $this->args['select_options'];
      }

      //Gather up any JS args this field type has
      $this->gatherJSArgs();
    }
  }

  /**
   * Stores the arguments that need passed to the javascript file.
   * This information is collected by the metaBox object, and the aggregate results are enqueued to the script
   */
  private function gatherJSArgs(){
    if($this->hasErrors()) return; //Bail early if there are errors
    if($this->type === 'gallery' || $this->type === 'imageupload'){
      $this->jsArgs = ['fieldType' => $this->id,'previewTarget' => $this->previewTarget, 'inputTarget' => $this->inputTarget, 'setToPostID' => get_option('media_selector_attachment_id')];
    }
    elseif($this->type === 'srmpicker'){
      wp_enqueue_style('admin-beer-style', EBL_ASSETS_URL.'css/admin-beer.css');
      $this->jsArgs = ['inputTarget' => $this->inputTarget];
    }
    elseif($this->type === 'glassshape'){
      wp_enqueue_style('beer-glass', EBL_ASSETS_URL.'css/beer-glass.css');
      $this->jsArgs = ['inputTarget' => $this->inputTarget];
    }
  }

  /**
   * Returns the current field ID
   * @return int
   */
  public function fieldID(){
    return self::$fieldID;
  }

  /**
   * Check for errors before we fire up the metabox
   * @return bool|\WP_Error
   */
  function checkForErrors(){
    if(!isset($this->args['name'])) return $this->throwError('metaField01', 'This meta field is missing a name value in the arguments. Please pass a "name" in the args array. (Example: ["name" => "name of meta field"])');
    if(isset($this->args['type'])){
      if(!in_array($this->args['type'], self::EBL_SUPPORTED_META_FIELDS)){
        return $this->throwError('metaField02', 'This meta field is calling an input type of '.$this->args['type'].'. Currently, Easy Beer Lister only supports "input", "gallery", and "srmpicker" types.');
      }
    }

    return false;
  }

  /**
   * Build input args
   * @return string
   */
  public function inputArgs(){
    if($this->hasErrors()) return false; //Bail early if there are errors
    $result = '';
    foreach($this->inputArgs as $key => $input_arg){
      $result .= $key.'="'.$input_arg.'"';
    }

    return $result;
  }

}