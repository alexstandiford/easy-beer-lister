<?php
/**
 * Creates an easy way to loop through admin fields for Easy Beer Lister
 * Used in meta box fields, options pages, etc
 * @author: Alex Standiford
 * @date  : 11/12/17
 */


namespace ebl\admin;


use ebl\core\ebl;

if(!defined('ABSPATH')) exit;

class fieldLoop extends ebl{

  public $field;
  public $fields;
  public $jsArgs = [];
  public $inLoop = false;
  public $id;
  public $name;
  public static $postID;
  public $meta;

  public function __construct($fields,$name){
    $this->fields = apply_filters($this->prefix('admin_fields'), $fields, $fields);
    $this->field = new field($this->fields[0]);
    $this->name = $name;
    $this->id = sanitize_title($this->prefix($this->name));
    $this->metaKey = str_replace('-', '_', $this->id);
    $this->field = new field($this->fields[0]);
    if(!empty($this->field->jsArgs)) $this->jsArgs[$this->field->fieldID()] = $this->field->jsArgs; //Capture the js args to pass to the script afterward

    parent::__construct();
  }

  /**
   * Check to see if there are any meta fields to display
   * @return bool
   */
  public function haveFields(){

    //If we haven't done the loop yet, return true. theField() sets up the inLoop variable.
    if($this->inLoop == false){
      return true;
    }

    //If we have fields left, return true
    if(count($this->fields) > 1){
      return true;
    };

    //Otherwise, enqueue scripts and return false
    $this->enqueue();

    return false;
  }

  /**
   * Enqueues the scripts after the loop has been ran.
   * Passes the jsArgs to the script
   * @return bool|\WP_Error
   */
  protected function enqueue(){
    if(!$this->inLoop) return $this->throwError('fieldLoop05', 'The protected method enqueue() can only run inside the loop.');
    wp_enqueue_media();
    wp_dequeue_script('admin-media-beer');
    wp_register_script('admin-media-beer', EBL_ASSETS_URL.'js/admin-media-beer.js', ['jquery'], EBL_VERSION);
    wp_localize_script('admin-media-beer', 'eblAdmin', $this->jsArgs);
    wp_enqueue_script('admin-media-beer');

    return false;
  }

  /**
   * Creates the next meta box field object, and shifts the array
   */
public function theField(){
    if($this->inLoop == false){
      echo wp_nonce_field(EBL_PATH, $this->prefix('beer_nonce'));
      $this->inLoop = true;
    }
    else{
      $this->field = new field($this->fields[1]);
      if(!empty($this->field->jsArgs)) $this->jsArgs[$this->field->fieldID()] = $this->field->jsArgs; //Capture the js args to pass to the script afterward
      array_shift($this->fields);
    }
  }

  /**
   * Loads the field loop template
   * @return null|\WP_Error
   */
  public function loadTemplate(){
    $template_name = apply_filters($this->prefix('admin_'.$this->id.'_template_location'), str_replace('_','-',field::EBL_FIELD_TEMPLATE_DIR.$this->id.'.php'));
    if(!file_exists($template_name)) return $this->throwError('fieldLoop03', 'The template located at '.$template_name.' Could not be found.');
    if($this->fileExists($template_name)) include($template_name);

    return null;
  }


  /**
   * Get a single field input, based on the type
   * Template files are located in plugin_dir/templates/admin/
   * @return string|\WP_Error
   */
  public function input(){
    if($this->inLoop == false) return $this->throwError('fieldLoop04', 'metaBox->input() cannot be used outside of the loop');
    $this->metaValue = $this->getMetaValue() ? esc_attr($this->getMetaValue()) : '';
    $file = apply_filters('ebl_field_'.$this->field->type.'_template_location', field::EBL_FIELD_TEMPLATE_DIR.'ebl-field-'.$this->field->type.'.php');
    if($this->fileExists($file)) include($file);

    return $this->field->type;
  }


  /**
   * Gets the current meta value
   * @return mixed
   */
  public function getMetaValue(){
    if(!$this->inLoop) $this->throwError('fieldLoop06', 'The method getMetaValue only works inside the loop');

    return isset($this->meta[str_replace('-', '_', $this->field->metaValue)]) ? $this->meta[str_replace('-', '_', $this->field->metaValue)] : '';
  }


  /**
   * Required on every object to check for errors
   * @return mixed
   */
  protected function checkForErrors(){
    return null; //No checks (for now)
  }


  /**
   * Gets the selected glass layout
   */
  public function getSelectedLayout(){
    if(!$this->inLoop) return $this->throwError('eblmeta02', 'getSelectedLayout must run inside a field loop to work properly.');
    $meta_value = explode(',',$this->field->metaValue);
    if(isset($meta_value[1])) $meta_value = explode('-',$meta_value[1]);
    if($meta_value[0] == 'bottle'){
      $selected_layout = 'bottle';
    }
    elseif(isset($meta_value[1]) && $meta_value[1] == 'bottle'){
      $selected_layout = 'glass-bottle';
    }
    else{
      $selected_layout = 'glass';
    }

    return $selected_layout;
  }

}